@extends('layouts.admin')

@section('title', 'Einstellungen')

@section('content')
  <h1>Einstellungen</h1>

  <div class="settings-section">
    <h2>Benutzerrechte</h2>
    <p class="settings-section__desc">Lege fest, welche Bereiche ein Client-Benutzer sehen und bearbeiten darf.</p>

    @if ($clients->isEmpty())
      <p class="settings-empty">Keine Client-Benutzer vorhanden.</p>
    @else
      @foreach ($clients as $client)
        <div class="permission-card">
          <div class="permission-card__header">
            <div class="permission-card__user">
              <span class="material-symbols-rounded">account_circle</span>
              <div>
                <strong>{{ $client->name }}</strong>
                <span>{{ $client->email }}</span>
              </div>
            </div>
          </div>

          <form method="POST" action="{{ route('admin.settings.update', $client) }}">
            @csrf
            @method('PUT')

            <div class="permission-card__grid">
              @foreach ($permissions as $permission)
                <label class="permission-checkbox">
                  <input
                    type="checkbox"
                    name="permissions[]"
                    value="{{ $permission->name }}"
                    {{ $client->hasPermissionTo($permission->name) ? 'checked' : '' }}
                  />
                  <span class="permission-checkbox__label">{{ $permission->name }}</span>
                </label>
              @endforeach
            </div>

            <div class="permission-card__footer">
              <button type="submit" class="btn btn-save">Speichern</button>
            </div>
          </form>
        </div>
      @endforeach
    @endif
  </div>
@endsection
