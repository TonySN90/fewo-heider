<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
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

    <a href="{{ route('admin.profile') }}" class="topbar__user {{ request()->routeIs('admin.profile') ? 'topbar__user--active' : '' }}">
      <span class="material-symbols-rounded">account_circle</span>
      {{ auth()->user()->full_name }}
    </a>
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
            Übersicht
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
        @role('super-admin')
        <li>
          <a href="{{ route('admin.overview') }}"
             class="sidebar__link {{ request()->routeIs('admin.overview') ? 'sidebar__link--active' : '' }}">
            <span class="material-symbols-rounded sidebar__icon">dashboard</span>
            Admin-Übersicht
          </a>
        </li>
        @endrole
        @hasanyrole('admin|super-admin')
        <li>
          <a href="{{ route('admin.users') }}"
             class="sidebar__link {{ request()->routeIs('admin.users*') ? 'sidebar__link--active' : '' }}">
            <span class="material-symbols-rounded sidebar__icon">group</span>
            Benutzer
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
    @if (session('info'))
      <div class="alert alert--info">{{ session('info') }}</div>
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

  let activeTemplateId = null;

  function openModal(title, templateId) {
    const tpl = document.getElementById(templateId);
    activeTemplateId = templateId;
    modalTitle.textContent = title;
    modalBody.innerHTML = tpl.innerHTML;
    modalOverlay.classList.add('is-open');
    modalBody.querySelector('input, select')?.focus();
    initModalLivePreview();
    initModalPageEdit(templateId);
  }

  function initModalPageEdit(templateId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ── Inline-Save für contenteditable [data-field] Elemente ────────────────
    modalBody.querySelectorAll('[data-field]').forEach(el => {
      const isMultiline = el.dataset.multiline === 'true';

      el.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !isMultiline) { e.preventDefault(); el.blur(); }
      });

      el.addEventListener('blur', async () => {
        const text  = (el.innerText || el.textContent || '').replace(/\n{3,}/g, '\n\n').trim();
        const url   = el.dataset.url;
        const field = el.dataset.field;
        if (!url) return;

        // Summary der zugehörigen page-group aktualisieren
        if (field === 'title' && templateId) {
          const trigger = document.querySelector(`[onclick*="${templateId}"]`);
          const summary = trigger?.closest('details')?.querySelector('.page-group__summary');
          const strong = summary?.querySelector('strong');
          if (strong) strong.textContent = text;
        }

        const body = new FormData();
        body.append('_method', 'PUT');
        body.append('_token', csrfToken);
        body.append(field, text);

        // title ist required — immer mitsenden
        if (field !== 'title') {
          const titleEl = modalBody.querySelector('[data-field="title"]');
          const titleVal = titleEl
            ? (titleEl.value !== undefined ? titleEl.value : (titleEl.innerText || titleEl.textContent || '')).trim()
            : '';
          body.append('title', titleVal);
        }

        try {
          const res = await fetch(url, { method: 'POST', body, headers: { 'Accept': 'application/json' } });
          if (res.ok) {
            el.classList.add('preview-editable--saved');
            setTimeout(() => el.classList.remove('preview-editable--saved'), 1200);
          } else {
            el.classList.add('preview-editable--error');
            setTimeout(() => el.classList.remove('preview-editable--error'), 2000);
          }
        } catch {
          el.classList.add('preview-editable--error');
          setTimeout(() => el.classList.remove('preview-editable--error'), 2000);
        }
      });
    });

    // ── Bild-Upload via Klick auf Hero ────────────────────────────────────────
    const heroWrap  = modalBody.querySelector('.modal-page-hero');
    const imgUpload = modalBody.querySelector('.modal-page-hero__upload');
    if (heroWrap && imgUpload) {
      heroWrap.addEventListener('click', e => {
        if (!e.target.closest('[contenteditable]')) imgUpload.click();
      });

      imgUpload.addEventListener('change', async () => {
        const file = imgUpload.files[0];
        if (!file) return;

        // Sofort lokal vorschauen
        const reader = new FileReader();
        reader.onload = ev => {
          heroWrap.style.backgroundImage = `url('${ev.target.result}')`;
        };
        reader.readAsDataURL(file);

        // AJAX Upload
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('_token', csrfToken);
        formData.append('title', modalBody.querySelector('[data-field="title"]')?.innerText.trim() ?? '');
        formData.append('cover_image', file);

        try {
          await fetch(imgUpload.dataset.url, { method: 'POST', body: formData });
        } catch { /* silent */ }
      });
    }

    // ── Settings: input/select/checkbox per change/blur speichern ───────────
    const settings = modalBody.querySelector('.modal-page-settings');
    if (settings) {
      const settingsUrl = settings.dataset.url;

      async function saveField(field, value, indicatorEl) {
        // Pflichtfeld title immer mitsenden
        const titleEl = modalBody.querySelector('[data-field="title"]');
        const titleVal = titleEl
          ? (titleEl.value !== undefined ? titleEl.value : (titleEl.innerText || titleEl.textContent || '')).trim()
          : '';

        const body = new FormData();
        body.append('_method', 'PUT');
        body.append('_token', csrfToken);
        if (field !== 'title') body.append('title', titleVal);
        // nav_label_sync schreibt auf nav_label
        body.append(field === 'nav_label_sync' ? 'nav_label' : field, value);

        try {
          const res = await fetch(settingsUrl, { method: 'POST', body, headers: { 'Accept': 'application/json' } });
          if (indicatorEl) {
            indicatorEl.classList.add(res.ok ? 'preview-editable--saved' : 'preview-editable--error');
            setTimeout(() => {
              indicatorEl.classList.remove('preview-editable--saved', 'preview-editable--error');
            }, 1200);
          }
        } catch { /* silent */ }
      }

      // <input type="text"> — blur speichert
      settings.querySelectorAll('input[data-field]').forEach(input => {
        if (input.type === 'checkbox') return;
        input.addEventListener('keydown', e => { if (e.key === 'Enter') input.blur(); });
        input.addEventListener('blur', function () {
          // Summary der zugehörigen page-group aktualisieren
          if (templateId) {
            const trigger = document.querySelector(`[onclick*="${templateId}"]`);
            const summary = trigger?.closest('details')?.querySelector('.page-group__summary');
            if (summary) {
              if (this.dataset.field === 'title') {
                const strong = summary.querySelector('strong');
                if (strong) strong.textContent = this.value.trim();
              } else if (this.dataset.field === 'nav_label') {
                const meta = summary.querySelector('.page-group__meta');
                if (meta) {
                  const slug = meta.textContent.match(/\/\S+$/)?.[0] ?? '';
                  meta.textContent = `Nav: \u201e${this.value.trim()}\u201c \u00a0\u00b7\u00a0 ${slug}`;
                }
              }
            }
          }
          saveField(this.dataset.field, this.value.trim(), this);
        });
      });

      // <select> — change speichert
      settings.querySelectorAll('select[data-field]').forEach(sel => {
        sel.addEventListener('change', function () {
          saveField(this.dataset.field, this.value, this);
        });
      });

      // <input type="checkbox"> — change speichert
      settings.querySelectorAll('input[type="checkbox"][data-field]').forEach(cb => {
        cb.addEventListener('change', function () {
          saveField(this.dataset.field, this.checked ? '1' : '0');
        });
      });
    }
  }

  function initModalLivePreview() {
    const titleInput = modalBody.querySelector('[name="nav_label"], [name="title"]');
    const descInput  = modalBody.querySelector('[name="description"]');
    const prevTitle  = modalBody.querySelector('.modal-hero-preview__title');
    const prevDesc   = modalBody.querySelector('.modal-hero-preview__desc');
    if (prevTitle) {
      if (titleInput) titleInput.addEventListener('input', () => {
        prevTitle.textContent = titleInput.value || prevTitle.textContent;
      });
      if (descInput) descInput.addEventListener('input', () => {
        prevDesc.textContent = descInput.value;
      });
    }

    // Intro-Vorschau (Kategorie-Modal)
    const introHeadingInput = modalBody.querySelector('[name="intro_heading"]');
    const introTextInput    = modalBody.querySelector('[name="intro_text"]');
    const prevIntroHeading  = modalBody.querySelector('.modal-intro-preview__heading');
    const prevIntroText     = modalBody.querySelector('.modal-intro-preview__text');
    if (introHeadingInput && prevIntroHeading) {
      introHeadingInput.addEventListener('input', () => {
        prevIntroHeading.textContent = introHeadingInput.value;
      });
    }
    if (introTextInput && prevIntroText) {
      introTextInput.addEventListener('input', () => {
        prevIntroText.textContent = introTextInput.value;
      });
    }
  }

  function closeModal() {
    if (activeTemplateId) {
      const tpl = document.getElementById(activeTemplateId);
      if (tpl) {
        // Input-Properties ins Attribut übertragen, damit innerHTML sie enthält
        modalBody.querySelectorAll('input[type="text"], input[type="hidden"], textarea').forEach(el => {
          el.setAttribute('value', el.value);
        });
        modalBody.querySelectorAll('input[type="checkbox"]').forEach(el => {
          el.checked ? el.setAttribute('checked', '') : el.removeAttribute('checked');
        });
        modalBody.querySelectorAll('select').forEach(sel => {
          Array.from(sel.options).forEach(o => {
            o.selected ? o.setAttribute('selected', '') : o.removeAttribute('selected');
          });
        });
        tpl.innerHTML = modalBody.innerHTML;
      }
      activeTemplateId = null;
    }
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