<?php

namespace App\Http\Controllers;

use App\Models\Compound;
use App\Models\Appeal;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'user') {
            return redirect()->route('user.my-vehicle');
        }

        $today     = Carbon::today();
        $thirtyDaysAgo = Carbon::today()->subDays(30);

        // Row 2 — stat cards
        $compoundsThisMonth = Compound::whereBetween('issued_at', [$thirtyDaysAgo, Carbon::now()])->count();
        $compoundsToday     = Compound::whereDate('issued_at', $today)->count();
        $appealsUnresolved  = Appeal::where('result', 'pending')->count();
        $stickerUnresolved  = Vehicle::where('status', 'pending')->count();
        $paymentThisMonth = Compound::whereBetween('paid_at', [$thirtyDaysAgo, Carbon::now()])
            ->where('status', 'paid')
            ->with('offenceType')
            ->get()
            ->sum(function ($compound) {
                return $compound->is_discounted
                    ? $compound->offenceType->amount * 0.5
                    : $compound->offenceType->amount;
            });
        
        // Row 3 — top 5 officers by activity
        $topOfficers = User::where('role', 'officer')
            ->withCount([
                'compoundsIssued as compounds_count',
            ])
            ->get()
            ->map(function ($officer) {
                $appeals   = \App\Models\Appeal::where('reviewed_by', $officer->id)->count();
                $vehicles  = \App\Models\Vehicle::where('status', '!=', 'pending')
                                ->whereHas('compounds', function ($q) use ($officer) {
                                    // placeholder — vehicle approvals not tracked by officer yet
                                })->count();

                // Activity = compounds issued + appeals reviewed
                $officer->total_activity = $officer->compounds_count + $appeals;
                return $officer;
            })
            ->sortByDesc('total_activity')
            ->take(5)
            ->values();

        // Row 3 — weekly officer activity (Mon to Sun of current week)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weeklyActivity = [];
        $weeklyCompounds = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);

            $compounds = Compound::whereDate('issued_at', $day)->count();
            $appeals   = Appeal::whereDate('updated_at', $day)
                            ->where('result', '!=', 'pending')->count();

            $weeklyActivity[]  = $compounds + $appeals;
            $weeklyCompounds[] = $compounds;
        }

        $weekLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        return view('dashboard', compact(
            'compoundsThisMonth',
            'compoundsToday',
            'appealsUnresolved',
            'stickerUnresolved',
            'paymentThisMonth',
            'topOfficers',
            'weeklyActivity',
            'weeklyCompounds',
            'weekLabels'
        ));
    }
}