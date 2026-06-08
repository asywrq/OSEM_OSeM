<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'matric_or_staff_no' => 'nullable|string|max:50|unique:users',
            'email'              => 'required|email|unique:users',
            'password'           => 'required|string|min:8|confirmed',
            'role'               => 'required|in:admin,officer,user',
            'is_active'          => 'boolean',
        ]);

        User::create([
            'name'               => $request->name,
            'matric_or_staff_no' => $request->matric_or_staff_no,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'role'               => $request->role,
            'is_active'          => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'matric_or_staff_no' => 'nullable|string|max:50|unique:users,matric_or_staff_no,' . $user->id,
            'email'              => 'required|email|unique:users,email,' . $user->id,
            'password'           => 'nullable|string|min:8|confirmed',
            'role'               => 'required|in:admin,officer,user',
            'is_active'          => 'boolean',
        ]);

        $data = $request->only('name', 'matric_or_staff_no', 'email', 'role', 'is_active');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}
