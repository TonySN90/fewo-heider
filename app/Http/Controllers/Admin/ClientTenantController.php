<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientTenantController extends Controller
{
    public function switch(Request $request)
    {
        $tenantId = $request->validate([
            'tenant_id' => ['required', 'integer'],
        ])['tenant_id'];

        abort_unless(
            $request->user()->tenants()->where('tenants.id', $tenantId)->exists(),
            403
        );

        $request->session()->put('current_tenant_id', $tenantId);

        return redirect()->route('admin.dashboard');
    }
}
