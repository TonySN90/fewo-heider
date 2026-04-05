<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin') – Ferienwohnung Heider</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />

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
          <span class="material-symbols-rounded sidebar__icon">dashboard</span>
          Dashboard
        </a>
      </li>
      <li>
        <a href="{{ route('admin.bookings') }}"
           class="sidebar__link {{ request()->routeIs('admin.bookings') ? 'sidebar__link--active' : '' }}">
          <span class="material-symbols-rounded sidebar__icon">calendar_month</span>
          Buchungen
        </a>
      </li>
      <li>
        <a href="{{ route('admin.seasons') }}"
           class="sidebar__link {{ request()->routeIs('admin.seasons') ? 'sidebar__link--active' : '' }}">
          <span class="material-symbols-rounded sidebar__icon">euro</span>
          Preise
        </a>
      </li>
      <li>
        <a href="{{ route('admin.templates') }}"
           class="sidebar__link {{ request()->routeIs('admin.templates') ? 'sidebar__link--active' : '' }}">
          <span class="material-symbols-rounded sidebar__icon">palette</span>
          Templates
        </a>
      </li>
    </ul>

    <div class="sidebar__footer">
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="sidebar__logout">
          <span class="material-symbols-rounded sidebar__icon">logout</span>
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

{{-- Modal --}}
<div class="modal-overlay" id="modalOverlay">
  <div class="modal" id="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal__header">
      <h3 class="modal__title" id="modalTitle"></h3>
      <button class="modal__close" id="modalClose" aria-label="Schließen">&times;</button>
    </div>
    <div class="modal__body" id="modalBody"></div>
  </div>
</div>

<script>
  // Sidebar
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

  // Modal
  const modalOverlay = document.getElementById('modalOverlay');
  const modalTitle   = document.getElementById('modalTitle');
  const modalBody    = document.getElementById('modalBody');

  function openModal(title, templateId) {
    const tpl = document.getElementById(templateId);
    modalTitle.textContent = title;
    modalBody.innerHTML = tpl.innerHTML;
    modalOverlay.classList.add('is-open');
    modalBody.querySelector('input, select')?.focus();
  }

  function closeModal() {
    modalOverlay.classList.remove('is-open');
  }

  document.getElementById('modalClose').addEventListener('click', closeModal);

  modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) closeModal();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });
</script>

@stack('scripts')
</body>
</html>