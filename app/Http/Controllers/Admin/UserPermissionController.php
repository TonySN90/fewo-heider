<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    public function index()
    {
        $users       = User::with('roles', 'tenants')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('admin.users', compact('users', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->hasRole('super-admin'), 403);

        $user->syncPermissions($request->input('permissions', []));

        return back()->with('success', 'Rechte für ' . $user->full_name . ' gespeichert.');
    }

    public function updateProfile(Request $request, User $user)
    {
        abort_if($user->hasRole('super-admin'), 403);

        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name'  => ['nullable', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'password'   => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Profil von ' . $user->full_name . ' gespeichert.');
    }
}