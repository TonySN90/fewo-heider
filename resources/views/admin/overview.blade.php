@extends('layouts.admin')

@section('title', 'Admin-Übersicht')

@section('content')
  <h1>Admin-Übersicht</h1>

  {{-- ── Instanz-Statistiken ── --}}
  <p class="platform-section-label">Instanzen</p>
  <div class="stat-grid">

    <div class="stat-card">
      <div class="stat-card__label">Instanzen gesamt</div>
      <div class="stat-card__value">{{ $totalTenants }}</div>
      <div class="stat-card__sub">{{ $activeTenants }} aktiv</div>
    </div>

    <div class="stat-card">
      <div class="stat-card__label">Inaktive Instanzen</div>
      <div class="stat-card__value {{ ($totalTenants - $activeTenants) === 0 ? 'stat-card__value--empty' : '' }}">
        {{ $totalTenants - $activeTenants }}
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-card__label">Mit aktiver Saison</div>
      <div class="stat-card__value">
        {{ collect($tenantRows)->filter(fn ($r) => $r['hasActiveSeason'])->count() }}
      </div>
      <div class="stat-card__sub">von {{ $totalTenants }} Instanzen</div>
    </div>

    <div class="stat-card">
      <div class="stat-card__label">Buchungen {{ now()->year }}</div>
      <div class="stat-card__value">{{ collect($tenantRows)->sum('bookingsYear') }}</div>
      <div class="stat-card__sub">plattformweit</div>
    </div>

  </div>

  {{-- ── Benutzer-Statistiken ── --}}
  <p class="platform-section-label">Benutzer</p>
  <div class="stat-grid">

    <div class="stat-card">
      <div class="stat-card__label">Benutzer gesamt</div>
      <div class="stat-card__value">{{ $totalUsers }}</div>
    </div>

    <div class="stat-card">
      <div class="stat-card__label">Super-Admins</div>
      <div class="stat-card__value">{{ $usersByRole['super-admin'] ?? 0 }}</div>
    </div>

    <div class="stat-card">
      <div class="stat-card__label">Admins</div>
      <div class="stat-card__value">{{ $usersByRole['admin'] ?? 0 }}</div>
    </div>

    <div class="stat-card">
      <div class="stat-card__label">Clients</div>
      <div class="stat-card__value">{{ $usersByRole['client'] ?? 0 }}</div>
    </div>

  </div>

  {{-- ── Tabelle: Alle Instanzen ── --}}
  <div class="table-card">
    <div class="table-card__header">
      <h2>Alle Instanzen</h2>
      <a href="{{ route('admin.tenants.create') }}" class="btn btn-add">
        <span class="material-symbols-rounded">add</span>
        Neue Instanz
      </a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Domain</th>
          <th>Template</th>
          <th>Status</th>
          <th>Saison</th>
          <th>Nutzer</th>
          <th>Buchungen {{ now()->year }}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($tenantRows as $row)
          <tr>
            <td><strong>{{ $row['tenant']->name }}</strong></td>
            <td class="text-muted">{{ $row['tenant']->domain ?? '–' }}</td>
            <td>{{ $row['tenant']->template?->name ?? '–' }}</td>
            <td>
              @if ($row['tenant']->is_active)
                <span class="badge badge--green">Aktiv</span>
              @else
                <span class="badge badge--gray">Inaktiv</span>
              @endif
            </td>
            <td>
              @if ($row['hasActiveSeason'])
                <span class="badge badge--green">Ja</span>
              @else
                <span class="badge badge--gray">–</span>
              @endif
            </td>
            <td>{{ $row['tenant']->users->count() }}</td>
            <td>{{ $row['bookingsYear'] }}</td>
            <td>
              <div class="actions">
                <form method="POST" action="{{ route('admin.tenants.manage', $row['tenant']) }}">
                  @csrf
                  <button type="submit" class="btn btn-manage" title="Instanz verwalten">
                    <span class="material-symbols-rounded">login</span>
                    Verwalten
                  </button>
                </form>
                <a href="{{ route('admin.tenants.edit', $row['tenant']) }}" class="btn btn-edit" title="Bearbeiten">
                  <span class="material-symbols-rounded">edit</span>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="8">Noch keine Instanzen angelegt.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- ── Tabelle: Alle Benutzer ── --}}
  <div class="table-card">
    <div class="table-card__header">
      <h2>Alle Benutzer</h2>
      <a href="{{ route('admin.users') }}" class="btn btn-edit" title="Benutzerverwaltung">
        <span class="material-symbols-rounded">manage_accounts</span>
      </a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>E-Mail</th>
          <th>Rolle</th>
          <th>Instanzen</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($userRows as $user)
          <tr>
            <td><strong>{{ $user->full_name }}</strong></td>
            <td class="text-muted">{{ $user->email }}</td>
            <td>
              @foreach ($user->getRoleNames() as $role)
                <span class="season-badge season-badge--{{ $role === 'super-admin' ? 'green' : 'blue' }}">
                  {{ $role }}
                </span>
              @endforeach
            </td>
            <td>
              @if ($user->tenants->count() > 0)
                {{ $user->tenants->count() }}
              @else
                <span class="text-muted">–</span>
              @endif
            </td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="4">Keine Benutzer vorhanden.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
