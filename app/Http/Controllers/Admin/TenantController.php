<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['template', 'users'])->get();

        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $templates = Template::orderBy('name')->get();
        $clients = User::role('client')->get();

        return view('admin.tenants.edit', compact('templates', 'clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash', 'unique:tenants,slug'],
            'domain' => ['nullable', 'string', 'max:253', 'unique:tenants,domain'],
            'template_id' => ['nullable', 'exists:templates,id'],
            'is_active' => ['boolean'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $tenant = Tenant::create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? null,
            'domain' => $data['domain'] ?? null,
            'template_id' => $data['template_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (! empty($data['user_ids'])) {
            $tenant->users()->sync($data['user_ids']);
        }

        if ($tenant->template_id) {
            $tenant->template->ensureTenantSections($tenant);
        }

        return redirect()->route('admin.tenants')->with('success', 'Instanz angelegt.');
    }

    public function edit(Tenant $tenant)
    {
        $templates = Template::orderBy('name')->get();
        $clients = User::role('client')->get();
        $assignedUserIds = $tenant->users()->pluck('users.id')->toArray();

        return view('admin.tenants.edit', compact('tenant', 'templates', 'clients', 'assignedUserIds'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash', 'unique:tenants,slug,'.$tenant->id],
            'domain' => ['nullable', 'string', 'max:253', 'unique:tenants,domain,'.$tenant->id],
            'template_id' => ['nullable', 'exists:templates,id'],
            'is_active' => ['boolean'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['exists:users,id'],
            'seo_description' => ['nullable', 'string', 'max:160'],
        ]);

        $tenant->update([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? null,
            'domain' => $data['domain'] ?? null,
            'template_id' => $data['template_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'seo_description' => $data['seo_description'] ?? null,
        ]);

        $tenant->users()->sync($data['user_ids'] ?? []);

        if ($tenant->template_id) {
            $tenant->load('template');
            $tenant->template->ensureTenantSections($tenant);
        }

        return redirect()->route('admin.tenants')->with('success', 'Instanz aktualisiert.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->users()->detach();
        $tenant->delete();

        return redirect()->route('admin.tenants')->with('success', 'Instanz gelöscht.');
    }

    public function manage(Request $request, Tenant $tenant)
    {
        $request->session()->put('admin_tenant_id', $tenant->id);

        return redirect()->route('admin.dashboard')->with('success', 'Kontext: '.$tenant->name);
    }

    public function clearContext(Request $request)
    {
        $request->session()->forget('admin_tenant_id');

        return redirect()->route('admin.tenants');
    }
}
