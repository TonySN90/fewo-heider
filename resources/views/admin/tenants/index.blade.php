@extends('layouts.admin')

@section('title', 'Instanzen')

@section('content')
  <h1>Instanzen</h1>

  <div class="table-card">
    <div class="table-card__header">
      <h2>Alle Instanzen ({{ $tenants->count() }})</h2>
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
          <th>Nutzer</th>
          <th>Aktiv</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($tenants as $tenant)
          <tr>
            <td><strong>{{ $tenant->name }}</strong></td>
            <td>{{ $tenant->domain ?? '–' }}</td>
            <td>{{ $tenant->template?->name ?? '–' }}</td>
            <td>{{ $tenant->users->count() }}</td>
            <td>
              @if($tenant->is_active)
                <span class="badge badge--green">Aktiv</span>
              @else
                <span class="badge badge--gray">Inaktiv</span>
              @endif
            </td>
            <td>
              <div class="actions">
                <form method="POST" action="{{ route('admin.tenants.manage', $tenant) }}">
                  @csrf
                  <button type="submit" class="btn btn-manage" title="Instanz verwalten">
                    <span class="material-symbols-rounded">login</span>
                    Verwalten
                  </button>
                </form>
                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-edit" title="Bearbeiten">
                  <span class="material-symbols-rounded">edit</span>
                </a>
                <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}"
                      onsubmit="return confirm('Instanz wirklich löschen?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-delete">
                    <span class="material-symbols-rounded">delete</span>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="6">Noch keine Instanzen angelegt.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
