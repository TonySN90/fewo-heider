<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Season;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['template', 'users'])->get();

        $bookingsPerTenant = Booking::whereYear('from', now()->year)
            ->selectRaw('tenant_id, COUNT(*) as count')
            ->groupBy('tenant_id')
            ->pluck('count', 'tenant_id');

        $activeTenantIds = Season::where('is_active', true)->pluck('tenant_id');

        $tenantRows = $tenants->map(fn ($t) => [
            'tenant' => $t,
            'hasActiveSeason' => $activeTenantIds->contains($t->id),
            'bookingsYear' => $bookingsPerTenant[$t->id] ?? 0,
        ]);

        $totalTenants = $tenants->count();
        $activeTenants = $tenants->where('is_active', true)->count();

        $totalUsers = User::count();
        $usersByRole = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_type', User::class)
            ->selectRaw('roles.name, COUNT(*) as count')
            ->groupBy('roles.name')
            ->pluck('count', 'name');

        $userRows = User::with('tenants')->get();

        return view('admin.overview', compact(
            'tenantRows',
            'totalTenants',
            'activeTenants',
            'totalUsers',
            'usersByRole',
            'userRows',
        ));
    }
}
