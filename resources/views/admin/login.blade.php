<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin – {{ $tenant?->name ?? 'Admin' }}</title>
  @vite(['resources/css/admin.scss'])
</head>
<body class="login-body">
  <div class="login-card">
    <div class="login-card__logo">
      <p class="login-card__logo-title">{{ $tenant?->name ?? 'Admin' }}</p>
      <p class="login-card__logo-sub">{{ $tenant ? 'Admin-Bereich' : 'Plattform-Verwaltung' }}</p>
    </div>


    @if ($errors->any())
      <div class="login-error">{{ $errors->first('email') }}</div>
    @endif

    <form method="POST" action="{{ url('/admin/login') }}">
      @csrf
      <label for="email">E-Mail</label>
      <input
        type="email"
        id="email"
        name="email"
        value="{{ old('email') }}"
        required
        autofocus
        autocomplete="email"
      />

      <label for="password">Passwort</label>
      <input
        type="password"
        id="password"
        name="password"
        required
        autocomplete="current-password"
      />

      <button type="submit">Einloggen</button>
    </form>
  </div>
</body>
</html>
