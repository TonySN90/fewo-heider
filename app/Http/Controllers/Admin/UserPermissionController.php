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
        $clients     = User::role('client')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('admin.settings', compact('clients', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->hasRole('admin'), 403);

        $user->syncPermissions($request->input('permissions', []));

        return back()->with('success', 'Rechte für ' . $user->full_name . ' gespeichert.');
    }

    public function updateProfile(Request $request, User $user)
    {
        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name'  => ['nullable', 'string', 'max:100'],
        ]);

        $user->update($data);

        return back()->with('success', 'Profil von ' . $user->full_name . ' gespeichert.');
    }
}
