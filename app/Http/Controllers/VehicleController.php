<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('user')->latest()->get();

        return view('admin.vehicles', compact('vehicles')); // ← remove .index
    }

    public function approve(Vehicle $vehicle)
    {
        $vehicle->update(['status' => 'approved']);

        return back()->with('success', "Vehicle {$vehicle->plate_no} has been approved.");
    }

    public function reject(Vehicle $vehicle)
    {
        $vehicle->update(['status' => 'rejected']);

        return back()->with('success', "Vehicle {$vehicle->plate_no} has been rejected.");
    }

    public function applications()
{
    $vehicles = Vehicle::with('user')
        ->where('status', 'pending')
        ->latest()
        ->get();

    return view('officer.vehicle-applications', compact('vehicles'));
}

public function approveApplication(Vehicle $vehicle)
{
    $vehicle->update(['status' => 'approved']);

    return back()->with('success', "Vehicle {$vehicle->plate_no} has been approved.");
}

public function rejectApplication(Vehicle $vehicle)
{
    $vehicle->update(['status' => 'rejected']);

    return back()->with('success', "Vehicle {$vehicle->plate_no} has been rejected.");
}
}