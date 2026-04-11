<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->hasRole('super-admin')
                ? redirect()->route('admin.overview')
                : redirect()->route('admin.dashboard');
        }

        $tenant = Tenant::where('domain', request()->getHost())
            ->where('is_active', true)
            ->first();

        return view('admin.login', compact('tenant'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if (! $user->hasRole('super-admin')) {
                $firstTenant = $user->tenants()->where('is_active', true)->first();
                if ($firstTenant) {
                    $request->session()->put('current_tenant_id', $firstTenant->id);
                }
            }

            $destination = $user->hasRole('super-admin')
                ? route('admin.overview')
                : route('admin.dashboard');

            return redirect()->intended($destination);
        }

        return back()->withErrors([
            'email' => 'E-Mail oder Passwort ist falsch.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
