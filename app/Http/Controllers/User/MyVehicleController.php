<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyVehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('user.my-vehicle', compact('vehicles'));
    }

    public function store(Request $request)
{
    $request->validate([
        'plate_no' => 'required|string|max:20',
        'type'     => 'required|in:car,motorcycle,van',
        'reason'   => 'required|string|max:500',
    ]);

    Vehicle::create([
        'user_id'  => Auth::id(),
        'plate_no' => strtoupper($request->plate_no),
        'type'     => $request->type,
        'reason'   => $request->reason,
        'status'   => 'pending',
        'is_active' => true,
    ]);

    return redirect()->route('user.my-vehicle')->with('success', 'Application submitted! An officer will review it shortly.');
}
}

