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
}