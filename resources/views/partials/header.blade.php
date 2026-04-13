<a href="#main-content" class="skip-link">Navigation überspringen</a>

<!-- ===== HEADER / NAVIGATION ===== -->
<header id="header">
  <div class="header__inner container">
    <a href="{{ url('/') }}" class="header__logo">
      @if ($headerSection?->field('brand_type') === 'logo' && $headerSection?->field('brand_logo'))
        @php
          $hLogoLight = $headerSection->field('brand_logo');
          $hLogoDark  = $headerSection->field('brand_logo_dark') ?: $hLogoLight;
          $hLogoAlt   = $headerSection->t('brand_name', 'Logo');
        @endphp
        <img src="{{ Storage::url($hLogoLight) }}" alt="{{ $hLogoAlt }}"
             class="header__logo-img header__logo-img--light" />
        <img src="{{ Storage::url($hLogoDark) }}" alt="{{ $hLogoAlt }}"
             class="header__logo-img header__logo-img--dark" />
      @else
        <span class="header__logo-text">{{ $headerSection?->t('brand_name', 'Ferienwohnung') }}</span>
        <span class="header__logo-sub">{{ $headerSection?->t('brand_sub') }}</span>
      @endif
    </a>

    <div class="header__controls">
      <div class="header__lang-switcher">
        <button class="header__lang-toggle" aria-haspopup="true" aria-expanded="false"
                aria-label="Sprache wählen">
          <span class="material-symbols-rounded">language</span>
          <span class="header__lang-current">{{ strtoupper(app()->getLocale()) }}</span>
          <span class="material-symbols-rounded header__lang-chevron">expand_more</span>
        </button>
        <div class="header__lang-dropdown" role="menu">
          @foreach (config('app.available_locales') as $code => $label)
            <a href="{{ route('locale.set', $code) }}" role="menuitem"
               class="header__lang-option {{ app()->getLocale() === $code ? 'active' : '' }}">{{ $label }}</a>
          @endforeach
        </div>
      </div>

      <div class="header__a11y">
        <button class="header__a11y-toggle" aria-label="{{ $ui['a11y_label'] }}" aria-expanded="false" aria-haspopup="true">
          <span class="material-symbols-rounded">accessibility_new</span>
        </button>
        <div class="header__a11y-dropdown" role="menu">
          <span class="header__a11y-label">{{ $ui['a11y_font_size'] }}</span>
          <div class="header__a11y-row">
            <button class="a11y__font-decrease" aria-label="{{ $ui['a11y_smaller'] }}">
              <span class="material-symbols-rounded">text_decrease</span>
              <span class="a11y__btn-label">{{ $ui['a11y_smaller'] }}</span>
            </button>
            <button class="a11y__font-reset" aria-label="{{ $ui['a11y_reset'] }}">
              <span class="material-symbols-rounded">restart_alt</span>
              <span class="a11y__btn-label">{{ $ui['a11y_reset'] }}</span>
            </button>
            <button class="a11y__font-increase" aria-label="{{ $ui['a11y_larger'] }}">
              <span class="material-symbols-rounded">text_increase</span>
              <span class="a11y__btn-label">{{ $ui['a11y_larger'] }}</span>
            </button>
          </div>
          <span class="header__a11y-label header__a11y-label--mt">{{ $ui['a11y_appearance'] }}</span>
          <div class="header__a11y-row">
            <button class="a11y__contrast-toggle" aria-label="{{ $ui['a11y_contrast'] }}" aria-pressed="false">
              <span class="material-symbols-rounded">contrast</span>
              <span class="a11y__btn-label">{{ $ui['a11y_contrast'] }}</span>
            </button>
            <button class="a11y__spacing-toggle" aria-label="{{ $ui['a11y_spacing'] }}" aria-pressed="false">
              <span class="material-symbols-rounded">format_line_spacing</span>
              <span class="a11y__btn-label">{{ $ui['a11y_spacing'] }}</span>
            </button>
            <button class="a11y__links-toggle" aria-label="{{ $ui['a11y_links'] }}" aria-pressed="false">
              <span class="material-symbols-rounded">link</span>
              <span class="a11y__btn-label">{{ $ui['a11y_links'] }}</span>
            </button>
            <button class="a11y__motion-toggle" aria-label="{{ $ui['a11y_motion'] }}" aria-pressed="false">
              <span class="material-symbols-rounded">animation</span>
              <span class="a11y__btn-label">{{ $ui['a11y_motion'] }}</span>
            </button>
          </div>
        </div>
      </div>

      <button class="header__theme-toggle" id="themeToggle" aria-label="Dark Mode umschalten">
        <span class="material-symbols-rounded header__theme-toggle__icon--light">light_mode</span>
        <span class="material-symbols-rounded header__theme-toggle__icon--dark">dark_mode</span>
      </button>

      <button class="header__hamburger" id="hamburger" aria-label="Menü öffnen" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>

    <nav class="header__nav" id="main-nav">
      <ul>
        @stack('header-nav-links')
      </ul>

      <div class="header__nav-utils">
        <div class="header__lang-switcher">
          <button class="header__lang-toggle" aria-haspopup="true" aria-expanded="false"
                  aria-label="Sprache wählen">
            <span class="material-symbols-rounded">language</span>
            <span class="header__lang-current">{{ strtoupper(app()->getLocale()) }}</span>
            <span class="material-symbols-rounded header__lang-chevron">expand_more</span>
          </button>
          <div class="header__lang-dropdown" role="menu">
            <a href="{{ route('locale.set', 'de') }}" role="menuitem"
               class="header__lang-option {{ app()->getLocale() === 'de' ? 'active' : '' }}">
              <span>🇩🇪</span> Deutsch
            </a>
            <a href="{{ route('locale.set', 'en') }}" role="menuitem"
               class="header__lang-option {{ app()->getLocale() === 'en' ? 'active' : '' }}">
              <span>🇬🇧</span> English
            </a>
          </div>
        </div>
        <button class="header__theme-toggle" aria-label="Dark Mode umschalten">
          <span class="material-symbols-rounded header__theme-toggle__icon--light">light_mode</span>
          <span class="material-symbols-rounded header__theme-toggle__icon--dark">dark_mode</span>
        </button>

        <div class="header__a11y">
          <button class="header__a11y-toggle" aria-label="{{ $ui['a11y_label'] }}" aria-expanded="false" aria-haspopup="true">
            <span class="material-symbols-rounded">accessibility_new</span>
          </button>
          <div class="header__a11y-dropdown" role="menu">
            <span class="header__a11y-label">{{ $ui['a11y_font_size'] }}</span>
            <div class="header__a11y-row">
              <button class="a11y__font-decrease" aria-label="{{ $ui['a11y_smaller'] }}">
                <span class="material-symbols-rounded">text_decrease</span>
                <span class="a11y__btn-label">{{ $ui['a11y_smaller'] }}</span>
              </button>
              <button class="a11y__font-reset" aria-label="{{ $ui['a11y_reset'] }}">
                <span class="material-symbols-rounded">restart_alt</span>
                <span class="a11y__btn-label">{{ $ui['a11y_reset'] }}</span>
              </button>
              <button class="a11y__font-increase" aria-label="{{ $ui['a11y_larger'] }}">
                <span class="material-symbols-rounded">text_increase</span>
                <span class="a11y__btn-label">{{ $ui['a11y_larger'] }}</span>
              </button>
            </div>
            <span class="header__a11y-label header__a11y-label--mt">{{ $ui['a11y_appearance'] }}</span>
            <div class="header__a11y-row">
              <button class="a11y__contrast-toggle" aria-label="{{ $ui['a11y_contrast'] }}" aria-pressed="false">
                <span class="material-symbols-rounded">contrast</span>
                <span class="a11y__btn-label">{{ $ui['a11y_contrast'] }}</span>
              </button>
              <button class="a11y__spacing-toggle" aria-label="{{ $ui['a11y_spacing'] }}" aria-pressed="false">
                <span class="material-symbols-rounded">format_line_spacing</span>
                <span class="a11y__btn-label">{{ $ui['a11y_spacing'] }}</span>
              </button>
              <button class="a11y__links-toggle" aria-label="{{ $ui['a11y_links'] }}" aria-pressed="false">
                <span class="material-symbols-rounded">link</span>
                <span class="a11y__btn-label">{{ $ui['a11y_links'] }}</span>
              </button>
              <button class="a11y__motion-toggle" aria-label="{{ $ui['a11y_motion'] }}" aria-pressed="false">
                <span class="material-symbols-rounded">animation</span>
                <span class="a11y__btn-label">{{ $ui['a11y_motion'] }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</header>
