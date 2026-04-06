@extends('layouts.admin')

@section('title', isset($tenant) ? 'Instanz bearbeiten' : 'Neue Instanz')

@section('content')
  <h1>{{ isset($tenant) ? 'Instanz bearbeiten' : 'Neue Instanz' }}</h1>

  <div class="form-card">
    <form method="POST"
          action="{{ isset($tenant) ? route('admin.tenants.update', $tenant) : route('admin.tenants.store') }}">
      @csrf
      @if(isset($tenant))
        @method('PUT')
      @endif

      <div class="form-grid">
        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" value="{{ old('name', $tenant->name ?? '') }}"
                 required maxlength="100" />
          @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
          <label>Slug <span class="form-hint">(z.B. fewo-mueller — für lokale Vorschau)</span></label>
          <input type="text" name="slug" value="{{ old('slug', $tenant->slug ?? '') }}"
                 maxlength="100" placeholder="z.B. fewo-mueller" />
          @error('slug')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
          <label>Domain <span class="form-hint">(z.B. fewo-mueller.de)</span></label>
          <input type="text" name="domain" value="{{ old('domain', $tenant->domain ?? '') }}"
                 maxlength="253" placeholder="optional" />
          @error('domain')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
          <label>Template</label>
          <select name="template_id">
            <option value="">– kein Template –</option>
            @foreach($templates as $template)
              <option value="{{ $template->id }}"
                {{ old('template_id', $tenant->template_id ?? '') == $template->id ? 'selected' : '' }}>
                {{ $template->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group form-group--checkbox">
          <label class="checkbox-label">
            <input type="hidden" name="is_active" value="0" />
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $tenant->is_active ?? true) ? 'checked' : '' }} />
            Instanz aktiv
          </label>
        </div>
      </div>

      <div class="form-section">
        <h3>Benutzer zuordnen</h3>
        <p class="form-hint">Client-User, die Zugriff auf diese Instanz erhalten sollen.</p>
        <div class="permission-card__grid">
          @foreach($clients as $client)
            <label class="permission-checkbox">
              <input type="checkbox" name="user_ids[]" value="{{ $client->id }}"
                     {{ in_array($client->id, $assignedUserIds ?? []) ? 'checked' : '' }} />
              <span class="permission-checkbox__label">
                {{ $client->name }}<br>
                <small>{{ $client->email }}</small>
              </span>
            </label>
          @endforeach
        </div>
      </div>

      <div class="form-actions">
        <a href="{{ route('admin.tenants') }}" class="btn btn-cancel">Abbrechen</a>
        <button type="submit" class="btn btn-save">Speichern</button>
      </div>
    </form>
  </div>
@endsection
