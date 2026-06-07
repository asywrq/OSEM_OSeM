<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\Compound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppealController extends Controller
{
    public function show(Compound $compound)
    {
        // Make sure this compound belongs to the logged-in user
        abort_if($compound->vehicle->user_id !== Auth::id(), 403);

        // Prevent appealing if already appealed or not unpaid
        abort_if($compound->status !== 'unpaid', 403);
        abort_if($compound->appeal()->exists(), 403);

        return view('user.appeal', compact('compound'));
    }

    public function store(Request $request, Compound $compound)
    {
        abort_if($compound->vehicle->user_id !== Auth::id(), 403);
        abort_if($compound->status !== 'unpaid', 403);
        abort_if($compound->appeal()->exists(), 403);

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        Appeal::create([
            'compound_id'  => $compound->id,
            'reviewed_by'  => null,
            'reason'       => $request->reason,
            'result'       => 'pending',
            'submitted_at' => now(),
        ]);

        $compound->update(['status' => 'appealing']);

        return redirect()->route('user.my-compounds')
            ->with('success', 'Appeal submitted successfully. An officer will review it shortly.');
    }
}