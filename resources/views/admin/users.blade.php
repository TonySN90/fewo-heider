@extends('layouts.admin')

@section('title', 'Benutzer')

@section('content')
  <div class="page-header">
    <h1>Benutzer</h1>
  </div>

  <div class="table-card">
    @if ($users->isEmpty())
      <p style="padding:1.75rem;color:#aaa">Keine Benutzer vorhanden.</p>
    @else
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>E-Mail</th>
            <th>Rolle</th>
            <th>Instanzen</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            <tr>
              <td>{{ $user->full_name }}</td>
              <td>{{ $user->email }}</td>
              <td>
                <span class="season-badge season-badge--{{ $user->getRoleNames()->first() === 'super-admin' ? 'green' : 'blue' }}">
                  {{ $user->getRoleNames()->first() ?? '–' }}
                </span>
              </td>
              <td>
                @forelse($user->tenants as $tenant)
                  <span class="season-badge season-badge--gray">{{ $tenant->name }}</span>
                @empty
                  <span style="color:#aaa">–</span>
                @endforelse
              </td>
              <td class="table__actions">
                @unless($user->hasRole('super-admin'))
                  <button class="btn btn-edit"
                          onclick="openModal('Profil bearbeiten', 'profile-tpl-{{ $user->id }}')"
                          title="Profil bearbeiten">
                    <span class="material-symbols-rounded">edit</span>
                  </button>
                  <button class="btn btn-edit"
                          onclick="openModal('Rechte verwalten', 'permissions-tpl-{{ $user->id }}')"
                          title="Rechte verwalten">
                    <span class="material-symbols-rounded">manage_accounts</span>
                  </button>
                @endunless
              </td>
            </tr>

            {{-- Modal: Profil --}}
            <template id="profile-tpl-{{ $user->id }}">
              <form method="POST" action="{{ route('admin.users.profile', $user) }}">
                @csrf
                @method('PUT')
                <div class="modal-form-grid">
                  <div>
                    <label>Vorname</label>
                    <input type="text" name="first_name" value="{{ $user->first_name }}" maxlength="100" />
                  </div>
                  <div>
                    <label>Nachname</label>
                    <input type="text" name="last_name" value="{{ $user->last_name }}" maxlength="100" />
                  </div>
                  <div class="modal-form-grid__full">
                    <label>E-Mail</label>
                    <input type="email" name="email" value="{{ $user->email }}" maxlength="150" required />
                  </div>
                  <div class="modal-form-grid__full">
                    <label>Neues Passwort <span class="form-field__hint">(leer lassen = unverändert)</span></label>
                    <input type="password" name="password" autocomplete="new-password" />
                  </div>
                  <div class="modal-form-grid__full">
                    <label>Passwort bestätigen</label>
                    <input type="password" name="password_confirmation" autocomplete="new-password" />
                  </div>
                </div>
                <div class="modal__actions">
                  <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
                  <button type="submit" class="btn btn-save">Speichern</button>
                </div>
              </form>
            </template>

            {{-- Modal: Rechte --}}
            <template id="permissions-tpl-{{ $user->id }}">
              <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="permission-card__grid" style="padding:0 0 1rem">
                  @foreach ($permissions as $permission)
                    <label class="permission-checkbox">
                      <input
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $permission->name }}"
                        {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}
                      />
                      <span class="permission-checkbox__label">{{ $permission->name }}</span>
                    </label>
                  @endforeach
                </div>
                <div class="modal__actions">
                  <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
                  <button type="submit" class="btn btn-save">Speichern</button>
                </div>
              </form>
            </template>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
@endsection