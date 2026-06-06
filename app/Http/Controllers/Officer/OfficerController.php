<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\Compound;
use App\Models\OffenceType;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    // ─── Appeal Reviews ───────────────────────────────────────────────────────

    public function appealReviews()
    {
        $appeals = Appeal::with([
            'compound.vehicle.user',
            'compound.offenceType',
        ])->where('result', 'pending')->latest()->get();

        return view('officer.appeal-reviews', compact('appeals'));
    }

    public function updateAppeal(Request $request, Appeal $appeal)
    {
        $request->validate([
            'result' => ['required', 'in:approved,rejected'],
        ]);

        $appeal->update(['result' => $request->result]);

        if ($request->result === 'approved') {
            $appeal->compound->update(['is_discounted' => true, 'status' => 'resolved']);
        } else {
            $appeal->compound->update(['status' => 'unpaid']);
        }

        return back()->with('success', 'Appeal ' . $request->result . ' successfully.');
    }

    // ─── Issue Compound ───────────────────────────────────────────────────────

    public function issueCompound()
    {
        $offenceTypes = OffenceType::all();

        $compounds = Compound::with(['vehicle.user', 'offenceType', 'officer'])
            ->where('officer_id', auth()->id())
            ->latest('issued_at')
            ->get();

        return view('officer.issue-compound', compact('offenceTypes', 'compounds'));
    }

    public function lookupPlate(Request $request)
    {
        $request->validate(['plate_no' => 'required|string']);

        $plate = strtoupper(trim($request->plate_no));

        $vehicle = Vehicle::with('user')
            ->where('plate_no', $plate)
            ->where('status', 'approved')
            ->first();

        if (!$vehicle) {
            return response()->json([
                'found'   => false,
                'message' => 'No approved vehicle found with that plate number.',
            ]);
        }

        return response()->json([
            'found'   => true,
            'id'      => $vehicle->id,
            'plate'   => $vehicle->plate_no,
            'type'    => ucfirst($vehicle->type),
            'owner'   => $vehicle->user->name,
            'sticker' => 'Valid',
        ]);
    }

    public function storeCompound(Request $request)
    {
        $request->validate([
            'vehicle_id'      => 'required|exists:vehicles,id',
            'offence_type_id' => 'required|exists:offence_types,id',
        ]);

        Compound::create([
            'vehicle_id'      => $request->vehicle_id,
            'officer_id'      => auth()->id(),
            'offence_type_id' => $request->offence_type_id,
            'status'          => 'unpaid',
            'is_discounted'   => false,
            'issued_at'       => now(),
        ]);

        return redirect()->route('officer.issue-compound')
            ->with('success', 'Compound issued successfully.');
    }
}
