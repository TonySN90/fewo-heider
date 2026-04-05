<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin') – Ferienwohnung Heider</title>
  @vite(['resources/css/admin.scss'])
</head>
<body class="admin-layout">

<header class="topbar">
  <span class="topbar__brand">Ferienwohnung <span>Heider</span> – Admin</span>
  <div class="topbar__actions">
    <a href="{{ url('/') }}" target="_blank">Website ansehen ↗</a>
    <button class="topbar__hamburger" id="sidebarToggle" aria-label="Navigation öffnen">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
</header>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="admin-shell">

  <nav class="sidebar" id="sidebar">
    <ul class="sidebar__nav">
      <li>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar__link {{ request()->routeIs('admin.dashboard') ? 'sidebar__link--active' : '' }}">
          <span class="sidebar__icon">&#9783;</span>
          Dashboard
        </a>
      </li>
      <li>
        <a href="{{ route('admin.bookings') }}"
           class="sidebar__link {{ request()->routeIs('admin.bookings') ? 'sidebar__link--active' : '' }}">
          <span class="sidebar__icon">&#128197;</span>
          Buchungen
        </a>
      </li>
      <li>
        <a href="{{ route('admin.seasons') }}"
           class="sidebar__link {{ request()->routeIs('admin.seasons') ? 'sidebar__link--active' : '' }}">
          <span class="sidebar__icon">&#128178;</span>
          Preise
        </a>
      </li>
    </ul>

    <div class="sidebar__footer">
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="sidebar__logout">
          <span class="sidebar__icon">&#8594;</span>
          Ausloggen
        </button>
      </form>
    </div>
  </nav>

  <main class="admin-main">
    @if (session('success'))
      <div class="alert alert--success">{{ session('success') }}</div>
    @endif

    @yield('content')
  </main>

</div>

<script>
  const toggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');

  function openSidebar() {
    sidebar.classList.add('is-open');
    overlay.classList.add('is-open');
  }

  function closeSidebar() {
    sidebar.classList.remove('is-open');
    overlay.classList.remove('is-open');
  }

  toggle.addEventListener('click', () => {
    sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
  });

  overlay.addEventListener('click', closeSidebar);
</script>

@stack('scripts')
</body>
</html>
