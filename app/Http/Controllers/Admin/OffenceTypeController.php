<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OffenceType;
use Illuminate\Http\Request;

class OffenceTypeController extends Controller
{
    public function index()
    {
        $offenceTypes = OffenceType::orderBy('name')->get();
        return view('admin.offences', compact('offenceTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:offence_types',
            'amount' => 'required|numeric|min:0',
        ]);

        OffenceType::create($request->only('name', 'amount'));

        return redirect()->route('admin.offences')->with('success', 'Offence type added successfully.');
    }

    public function update(Request $request, OffenceType $offence)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:offence_types,name,' . $offence->id,
            'amount' => 'required|numeric|min:0',
        ]);

        $offence->update($request->only('name', 'amount'));

        return redirect()->route('admin.offences')->with('success', 'Offence type updated successfully.');
    }

    public function destroy(OffenceType $offence)
    {
        $offence->delete();
        return redirect()->route('admin.offences')->with('success', 'Offence type deleted.');
    }
}
