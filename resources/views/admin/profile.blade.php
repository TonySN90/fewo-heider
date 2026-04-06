@extends('layouts.admin')

@section('title', 'Mein Profil')

@section('content')
  <div class="page-header">
    <h1>Mein Profil</h1>
  </div>

  <div class="table-card">
    <form method="POST" action="{{ route('admin.profile.update') }}">
      @csrf
      @method('PUT')

      <div class="section-edit-form">

        <h2 class="section-edit-form__heading">Persönliche Daten</h2>

        <div class="form-field">
          <label for="first_name">Vorname</label>
          <input type="text" id="first_name" name="first_name"
                 value="{{ old('first_name', auth()->user()->first_name) }}" maxlength="100" />
        </div>

        <div class="form-field">
          <label for="last_name">Nachname</label>
          <input type="text" id="last_name" name="last_name"
                 value="{{ old('last_name', auth()->user()->last_name) }}" maxlength="100" />
        </div>

        <div class="form-field">
          <label for="email">E-Mail</label>
          <input type="email" id="email" name="email"
                 value="{{ old('email', auth()->user()->email) }}" maxlength="150" required />
          @error('email')
            <span style="color:red;font-size:0.85rem">{{ $message }}</span>
          @enderror
        </div>

        <h2 class="section-edit-form__heading">Passwort ändern</h2>

        <div class="form-field">
          <label for="password">Neues Passwort <span class="form-field__hint">(leer lassen = unverändert)</span></label>
          <input type="password" id="password" name="password" autocomplete="new-password" />
          @error('password')
            <span style="color:red;font-size:0.85rem">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-field">
          <label for="password_confirmation">Passwort bestätigen</label>
          <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" />
        </div>

      </div>

      <div class="section-edit-form__actions">
        <button type="submit" class="btn btn-save">Speichern</button>
      </div>
    </form>
  </div>
@endsection
