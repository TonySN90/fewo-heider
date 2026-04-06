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
{{--  <span class="topbar__brand">{{ $currentTenant?->name ?? 'Admin-Login' }} </span>--}}

    <span class="topbar__user">
      <span class="material-symbols-rounded">account_circle</span>
      {{ auth()->user()->full_name }}
{{--      <span class="topbar__role">{{ auth()->user()->getRoleNames()->first() }}</span>--}}
    </span>
  <div class="topbar__actions">

    {{-- Aktiver Tenant-Kontext (Super-Admin in einer Instanz) --}}
    @if($currentTenant)
      <span class="topbar__tenant">
        <span class="material-symbols-rounded">apartment</span>
        {{ $currentTenant->name }}
        @if(auth()->user()->hasRole('super-admin') && session('admin_tenant_id'))
          <form method="POST" action="{{ route('admin.tenants.clear-context') }}" style="display:inline">
            @csrf
            <button type="submit" class="topbar__tenant-exit" title="Kontext verlassen">✕</button>
          </form>
        @endif
      </span>
    @endif

    {{-- Client: Instanz-Wechsler (wenn mehrere Instanzen) --}}
    @if(! auth()->user()->hasRole('super-admin') && auth()->user()->tenants->count() > 1)
      <form method="POST" action="{{ route('admin.tenant-switch') }}" class="topbar__tenant-switch">
        @csrf
        <select name="tenant_id" onchange="this.form.submit()">
          @foreach(auth()->user()->tenants as $t)
            <option value="{{ $t->id }}" {{ $currentTenant?->id === $t->id ? 'selected' : '' }}>
              {{ $t->name }}
            </option>
          @endforeach
        </select>
      </form>
    @endif

    @if($currentTenant?->domain)
      <a href="https://{{ $currentTenant->domain }}" target="_blank">Website ansehen ↗</a>
    @elseif($currentTenant?->slug)
      <a href="{{ route('tenant.preview', $currentTenant->slug) }}" target="_blank">Website ansehen ↗</a>
    @elseif(! $currentTenant)
      <a href="{{ url('/') }}" target="_blank">Website ansehen ↗</a>
    @endif
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
    {{-- Instanz-Bereich: nur wenn Kontext aktiv --}}
    @if($currentTenant)
    <div class="sidebar__section">
      <span class="sidebar__section-label">{{ $currentTenant->name }}</span>
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
        @can('manage seasons')
        <li>
          <a href="{{ route('admin.seasons') }}"
             class="sidebar__link {{ request()->routeIs('admin.seasons') ? 'sidebar__link--active' : '' }}">
            <span class="material-symbols-rounded sidebar__icon">euro</span>
            Preise
          </a>
        </li>
        @endcan
        @can('manage templates')
        <li>
          <a href="{{ route('admin.page-structure') }}"
             class="sidebar__link {{ request()->routeIs('admin.page-structure*') ? 'sidebar__link--active' : '' }}">
            <span class="material-symbols-rounded sidebar__icon">web</span>
            Seitenstruktur
          </a>
        </li>
        @endcan
      </ul>
    </div>
    @endif

    {{-- Plattform-Bereich: immer sichtbar --}}
    @hasanyrole('admin|super-admin')
    <div class="sidebar__section sidebar__section--platform">
      <span class="sidebar__section-label">Plattform</span>
      <ul class="sidebar__nav">
        @hasanyrole('admin|super-admin')
        <li>
          <a href="{{ route('admin.settings') }}"
             class="sidebar__link {{ request()->routeIs('admin.settings') ? 'sidebar__link--active' : '' }}">
            <span class="material-symbols-rounded sidebar__icon">manage_accounts</span>
            Einstellungen
          </a>
        </li>
        @endhasanyrole
        @role('super-admin')
        <li>
          <a href="{{ route('admin.templates') }}"
             class="sidebar__link {{ request()->routeIs('admin.templates*') ? 'sidebar__link--active' : '' }}">
            <span class="material-symbols-rounded sidebar__icon">palette</span>
            Templates
          </a>
        </li>
        <li>
          <a href="{{ route('admin.tenants') }}"
             class="sidebar__link {{ request()->routeIs('admin.tenants*') ? 'sidebar__link--active' : '' }}">
            <span class="material-symbols-rounded sidebar__icon">apartment</span>
            Instanzen
          </a>
        </li>
        @endrole
      </ul>
    </div>
    @endhasanyrole

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