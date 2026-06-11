<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Compound;
use Illuminate\Support\Facades\Auth;

class MyCompoundController extends Controller
{
    public function index()
    {
        $compounds = Compound::with(['vehicle', 'offenceType', 'appeal'])
            ->whereHas('vehicle', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->orderByDesc('issued_at')
            ->get();

        return view('user.my-compounds', compact('compounds'));
    }

    public function pay(Compound $compound)
    {
        abort_if($compound->vehicle->user_id !== Auth::id(), 403);
        abort_if(!in_array($compound->status, ['unpaid', 'resolved']), 403);

        $compound->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('user.my-compounds')
            ->with('success', 'Payment confirmed. Compound #' . $compound->id . ' is now marked as paid.');
    }
}