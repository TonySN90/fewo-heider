@extends('layouts.app')

@php
  $isEn = app()->getLocale() === 'en';
  $ui = $isEn ? [
    'gallery_btn'           => 'View Gallery',
    'contact_btn'           => 'Enquire Now',
    'nav_about'             => 'The Apartment',
    'nav_amenities'         => 'Amenities',
    'nav_gallery'           => 'Gallery',
    'nav_pricing'           => 'Prices',
    'nav_arrival'           => 'Getting Here',
    'nav_contact'           => 'Enquire',
    'nav_contact_footer'    => 'Contact',
    'pricing_eyebrow'       => 'Overview',
    'pricing_title'         => 'Prices & Availability',
    'pricing_table'         => 'Price Table',
    'pricing_plan'          => 'Availability Calendar',
    'col_season'            => 'Season',
    'col_period'            => 'Period',
    'col_night'             => 'per Night',
    'col_minstay'           => 'Min. Stay',
    'legend_free'           => 'Available',
    'legend_booked'         => 'Booked',
    'footer_nav'            => 'Navigation',
    'footer_contact'        => 'Contact',
    'gallery_all'           => 'View all :count photos',
    'impressum'             => 'Imprint',
    'datenschutz'           => 'Privacy Policy',
    'arrival_accommodation' => 'Your Accommodation',
    'arrival_tips'          => 'Travel Tips',
    'scroll_down'           => 'Scroll down',
    'scroll_top'            => 'Scroll to top',
  ] : [
    'gallery_btn'           => 'Galerie ansehen',
    'contact_btn'           => 'Jetzt anfragen',
    'nav_about'             => 'Die Wohnung',
    'nav_amenities'         => 'Ausstattung',
    'nav_gallery'           => 'Galerie',
    'nav_pricing'           => 'Preise',
    'nav_arrival'           => 'Anreise',
    'nav_contact'           => 'Anfragen',
    'nav_contact_footer'    => 'Kontakt',
    'pricing_eyebrow'       => 'Übersicht',
    'pricing_title'         => 'Preise & Verfügbarkeit',
    'pricing_table'         => 'Preistabelle',
    'pricing_plan'          => 'Belegungsplan',
    'col_season'            => 'Saison',
    'col_period'            => 'Zeitraum',
    'col_night'             => 'pro Nacht',
    'col_minstay'           => 'Mindestaufenthalt',
    'legend_free'           => 'Frei',
    'legend_booked'         => 'Belegt',
    'footer_nav'            => 'Navigation',
    'footer_contact'        => 'Kontakt',
    'gallery_all'           => 'Alle :count Bilder anschauen',
    'impressum'             => 'Impressum',
    'datenschutz'           => 'Datenschutz',
    'arrival_accommodation' => 'Ihre Unterkunft',
    'arrival_tips'          => 'Anreise-Tipps',
    'scroll_down'           => 'Nach unten scrollen',
    'scroll_top'            => 'Nach oben scrollen',
  ];

  $navLabels = [
    'about'     => ['href' => '#about',     'label' => $ui['nav_about']],
    'amenities' => ['href' => '#amenities', 'label' => $ui['nav_amenities']],
    'gallery'   => ['href' => '#gallery',   'label' => $ui['nav_gallery']],
    'pricing'   => ['href' => '#pricing',   'label' => $ui['nav_pricing']],
    'arrival'   => ['href' => '#arrival',   'label' => $ui['nav_arrival']],
  ];
@endphp

@section('content')

  <!-- ===== HEADER / NAVIGATION ===== -->
  <header id="header">
    <div class="header__inner container">
      <a href="#hero" class="header__logo">
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
          <a href="{{ route('locale.set', 'de') }}"
             class="header__lang-btn {{ app()->getLocale() === 'de' ? 'active' : '' }}">DE</a>
          <span class="header__lang-sep">|</span>
          <a href="{{ route('locale.set', 'en') }}"
             class="header__lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
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
          @foreach ($orderedSections as $sec)
            @if (isset($navLabels[$sec->section_key]))
              <li><a href="{{ $navLabels[$sec->section_key]['href'] }}">{{ $navLabels[$sec->section_key]['label'] }}</a></li>
            @endif
          @endforeach
          @foreach ($pageGroups as $group)
            <li><a href="{{ url('/' . $group->slug) }}">{{ $group->nav_label }}</a></li>
          @endforeach
          @if (in_array('contact', $visibleSections))
            <li><a href="#contact" class="nav__cta">{{ $ui['nav_contact'] }}</a></li>
          @endif
        </ul>

        <div class="header__nav-utils">
          <div class="header__lang-switcher">
            <a href="{{ route('locale.set', 'de') }}"
               class="header__lang-btn {{ app()->getLocale() === 'de' ? 'active' : '' }}">DE</a>
            <span class="header__lang-sep">|</span>
            <a href="{{ route('locale.set', 'en') }}"
               class="header__lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
          </div>
          <button class="header__theme-toggle" aria-label="Dark Mode umschalten">
            <span class="material-symbols-rounded header__theme-toggle__icon--light">light_mode</span>
            <span class="material-symbols-rounded header__theme-toggle__icon--dark">dark_mode</span>
          </button>
        </div>
      </nav>
    </div>
  </header>

  {{-- ===== SECTIONS (dynamisch nach sort_order) ===== --}}
  @foreach ($orderedSections as $sec)
    @switch($sec->section_key)

      @case('hero')
        <section id="hero" class="hero">
          <div class="hero__bg" @if($heroSection?->field('cover_image')) style="background-image:url('{{ Storage::url($heroSection->field('cover_image')) }}')" @endif></div>
          <div class="hero__overlay"></div>
          <div class="hero__content container">
            <p class="hero__eyebrow">{{ $heroSection?->t('eyebrow', 'Willkommen') }}</p>
            <h1 class="hero__title">{{ $heroSection?->t('title', 'Ihr Urlaub am Meer') }}</h1>
            <p class="hero__subtitle">{{ $heroSection?->t('subtitle') }}</p>
            <div class="hero__actions">
              @if (in_array('gallery', $visibleSections))
                <a href="#gallery" class="btn btn--primary">{{ $ui['gallery_btn'] }}</a>
              @endif
              @if (in_array('contact', $visibleSections))
                <a href="#contact" class="btn btn--outline">{{ $ui['contact_btn'] }}</a>
              @endif
            </div>
          </div>
          <a href="#about" class="hero__scroll" aria-label="{{ $ui['scroll_down'] }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </a>
        </section>
      @break

      @case('about')
        @php $u = $aboutSection; @endphp
        <section id="about" class="about section{{ $u?->field('bg_alt') === '1' ? ' section--alt' : '' }}">
          <div class="container">
            <div class="section__header">
              <p class="section__eyebrow">{{ $u?->t('eyebrow', 'Willkommen') }}</p>
              <h2 class="section__title">{{ $u?->t('title', 'Ferienwohnung') }}</h2>
              <div class="section__divider"></div>
            </div>
            <div class="about__grid">
              <div class="about__text">
                @if ($u?->t('text_1'))
                  <p class="about__intro">{{ $u->t('text_1') }}</p>
                @endif
                @if ($u?->t('text_2'))
                  <p>{{ $u->t('text_2') }}</p>
                @endif
                @if ($u?->t('text_3'))
                  <p>{{ $u->t('text_3') }}</p>
                @endif
                @if (in_array('contact', $visibleSections))
                  <a href="#contact" class="btn btn--primary">{{ $ui['contact_btn'] }}</a>
                @endif
              </div>
              <div class="about__highlights">
                @for ($i = 1; $i <= 6; $i++)
                  @php
                    $icon    = $u?->field("card_{$i}_icon");
                    $heading = $u?->t("card_{$i}_heading");
                    $text    = $u?->t("card_{$i}_text");
                  @endphp
                  @if ($icon && $heading)
                    <div class="highlight-card">
                      <div class="highlight-card__icon"><span class="material-symbols-rounded">{{ $icon }}</span></div>
                      <h3>{{ $heading }}</h3>
                      @if ($text)<p>{{ $text }}</p>@endif
                    </div>
                  @endif
                @endfor
              </div>
            </div>
          </div>
        </section>
      @break

      @case('amenities')
        @php $am = $amenitiesSection; @endphp
        <section id="amenities" class="amenities section{{ $am?->field('bg_alt') === '1' ? ' section--alt' : '' }}">
          <div class="container">
            <div class="section__header">
              <p class="section__eyebrow">{{ $am?->t('eyebrow', 'Was wir bieten') }}</p>
              <h2 class="section__title">{{ $am?->t('title', 'Ausstattung') }}</h2>
              <div class="section__divider"></div>
            </div>
            <div class="amenities__grid">
              @if ($am)
                @for ($i = 1; $i <= 50; $i++)
                  @php $icon = $am->field("amenity_{$i}_icon"); $label = $am->t("amenity_{$i}_label"); @endphp
                  @if ($icon && $label)
                    <div class="amenity-item">
                      <span class="amenity-item__icon material-symbols-rounded">{{ $icon }}</span>
                      <span class="amenity-item__label">{{ $label }}</span>
                    </div>
                  @elseif (!$icon && !$am->field("amenity_{$i}_label"))
                    @break
                  @endif
                @endfor
              @else
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">local_parking</span><span class="amenity-item__label">Kostenfreier Stellparkplatz</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">wifi</span><span class="amenity-item__label">W-LAN inklusive</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">radio</span><span class="amenity-item__label">Radio mit CD-Player</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">tv</span><span class="amenity-item__label">Satelliten-Fernsehen</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">cooking</span><span class="amenity-item__label">Herd & Backofen</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">kitchen</span><span class="amenity-item__label">Kühlschrank mit Tiefkühlfach</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">coffee_maker</span><span class="amenity-item__label">Kaffeemaschine</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">breakfast_dining</span><span class="amenity-item__label">Toaster</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">kettle</span><span class="amenity-item__label">Wasserkocher</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">egg_alt</span><span class="amenity-item__label">Eierkocher</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">bed</span><span class="amenity-item__label">Bettwäsche inklusive</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">dry</span><span class="amenity-item__label">Handtücher inklusive</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">door_front</span><span class="amenity-item__label">Separater Eingang</span></div>
                <div class="amenity-item"><span class="amenity-item__icon material-symbols-rounded">chair</span><span class="amenity-item__label">Gemütliche Sitzecke</span></div>
              @endif
            </div>
          </div>
        </section>
      @break

      @case('gallery')
        @php
          $visibleImgs = $galleryImages->take(6);
          $hiddenImgs  = $galleryImages->skip(6);
        @endphp
        <section id="gallery" class="gallery section{{ $gallerySection?->field('bg_alt') === '1' ? ' section--alt' : '' }}">
          <div class="container">
            <div class="section__header">
              <p class="section__eyebrow">{{ $gallerySection?->t('eyebrow', 'Eindrücke') }}</p>
              <h2 class="section__title">{{ $gallerySection?->t('title', 'Galerie') }}</h2>
              <div class="section__divider"></div>
            </div>
            <div class="gallery__grid" id="gallery-grid">
              @foreach ($visibleImgs as $img)
                <div class="gallery-item">
                  <a href="{{ $img->url() }}" data-fslightbox="gallery" data-caption="{{ $img->caption }}">
                    <img src="{{ $img->url() }}" alt="{{ $img->caption ?? 'Galerie-Bild' }}" loading="lazy" />
                  </a>
                  <div class="gallery-item__overlay"><span>{{ $img->caption }}</span></div>
                </div>
              @endforeach
              @foreach ($hiddenImgs as $img)
                <a href="{{ $img->url() }}" data-fslightbox="gallery"
                   data-caption="{{ $img->caption }}" aria-hidden="true" style="display:none"></a>
              @endforeach
            </div>
            @if ($galleryImages->count() > 6)
              <div class="gallery__footer">
                <button type="button"
                        class="btn btn--outline gallery__show-all"
                        onclick="fsLightboxInstances['gallery'].open(0)">
                  <span class="material-symbols-rounded">photo_library</span>
                  {{ str_replace(':count', $galleryImages->count(), $ui['gallery_all']) }}
                </button>
              </div>
            @endif
          </div>
        </section>
      @break

      @case('pricing')
        <section id="pricing" class="pricing section section--alt">
          <div class="container">
            <div class="section__header">
              <p class="section__eyebrow">{{ $ui['pricing_eyebrow'] }}</p>
              <h2 class="section__title">{{ $ui['pricing_title'] }}</h2>
              <div class="section__divider"></div>
            </div>
            <div class="pricing__layout">
              <div class="pricing__table-wrap">
                <h3 class="pricing__subtitle">{{ $ui['pricing_table'] }} <span id="pricing-year">{{ now()->year }}</span></h3>
                <div class="pricing__table-scroll">
                <table class="pricing__table">
                  <thead>
                    <tr>
                      <th>{{ $ui['col_season'] }}</th>
                      <th>{{ $ui['col_period'] }}</th>
                      <th>{{ $ui['col_night'] }}</th>
                      <th>{{ $ui['col_minstay'] }}</th>
                    </tr>
                  </thead>
                  <tbody id="seasons-tbody">
                    <tr>
                      <td colspan="4" style="text-align:center;color:#aaa;">Lädt …</td>
                    </tr>
                  </tbody>
                </table>
                </div>
                <div class="pricing__notes" id="pricing-notes-list">
                  <p style="color:#aaa;font-size:0.85rem">Lädt …</p>
                </div>
              </div>
              <div class="pricing__calendar-wrap">
                <div class="pricing__subtitle-row">
                  <h3 class="pricing__subtitle">{{ $ui['pricing_plan'] }}</h3>
                  <div class="cal-nav">
                    <button id="cal-prev" class="cal-nav__btn" aria-label="Vorheriger Monat">
                      <span class="material-symbols-rounded">chevron_left</span>
                    </button>
                    <button id="cal-next" class="cal-nav__btn" aria-label="Nächster Monat">
                      <span class="material-symbols-rounded">chevron_right</span>
                    </button>
                  </div>
                </div>
                <div class="calendar-legend">
                  <span class="legend-item legend-item--free">{{ $ui['legend_free'] }}</span>
                  <span class="legend-item legend-item--booked">{{ $ui['legend_booked'] }}</span>
                </div>
                <div id="booking-calendar" class="booking-calendar"></div>
              </div>
            </div>
          </div>
        </section>
      @break

      @case('arrival')
        <section id="arrival" class="map-section section{{ $arrivalSection?->field('bg_alt') === '1' ? ' section--alt' : '' }}">
          <div class="container">
            <div class="section__header">
              <p class="section__eyebrow">{{ $arrivalSection?->t('eyebrow') }}</p>
              <h2 class="section__title">{{ $arrivalSection?->t('title') }}</h2>
              <div class="section__divider"></div>
            </div>
            <div class="map-section__layout">
              <div class="map-section__address">
                <h3>{{ $arrivalSection?->t('address_subtitle') ?: $ui['arrival_accommodation'] }}</h3>
                <address>
                  <p class="address__name">{{ $arrivalSection?->field('address_name') }}</p>
                  <p>{{ $arrivalSection?->field('address_street') }}</p>
                  <p>{{ $arrivalSection?->field('address_city') }}</p>
                </address>
                @php
                  $arrivalPhone     = $arrivalSection?->field('phone') ?? '';
                  $arrivalPhoneHref = $arrivalSection?->field('phone_href') ?? '';
                  $arrivalEmail     = $arrivalSection?->field('email') ?? '';
                @endphp
                @if ($arrivalPhone || $arrivalEmail)
                  <div class="address__contact">
                    @if ($arrivalPhone)
                      <a href="tel:{{ $arrivalPhoneHref }}" class="contact-link">
                        <span class="contact-link__icon material-symbols-rounded">call</span>
                        {{ $arrivalPhone }}
                      </a>
                    @endif
                    @if ($arrivalEmail)
                      <a href="mailto:{{ $arrivalEmail }}" class="contact-link">
                        <span class="contact-link__icon material-symbols-rounded">mail</span>
                        {{ $arrivalEmail }}
                      </a>
                    @endif
                  </div>
                @endif
                @php
                  $arrivalHints = [];
                  for ($i = 1; $i <= 5; $i++) {
                    $icon = $arrivalSection?->field("hint_{$i}_icon");
                    $text = $arrivalSection?->t("hint_{$i}_text");
                    if ($icon || $text) {
                      $arrivalHints[] = ['icon' => $icon, 'text' => $text];
                    }
                  }
                @endphp
                @if (!empty($arrivalHints))
                  <div class="address__hints">
                    <h4>{{ $arrivalSection?->t('hints_title') ?: $ui['arrival_tips'] }}</h4>
                    <ul>
                      @foreach ($arrivalHints as $hint)
                        <li>
                          @if ($hint['icon'])
                            <span class="material-symbols-rounded">{{ $hint['icon'] }}</span>
                          @endif
                          {{ $hint['text'] }}
                        </li>
                      @endforeach
                    </ul>
                  </div>
                @endif
              </div>
              <div class="map-section__map">
                <div id="map"
                  data-lat="{{ $arrivalSection?->field('map_lat') ?: '54.3835' }}"
                  data-lng="{{ $arrivalSection?->field('map_lng') ?: '13.5632' }}"
                  data-name="{{ $arrivalSection?->field('brand_name') ?: ($tenant?->name ?? 'Musterferienwohnung') }}"
                  data-street="{{ $arrivalSection?->field('street') ?: 'Musterstraße 1' }}"
                  data-city="{{ $arrivalSection?->field('city') ?: '12345 Musterstadt' }}"
                  data-phone="{{ $arrivalSection?->field('phone') ?: '01234 56789' }}"
                  data-phone-href="{{ $arrivalSection?->field('phone_href') ?: '+4912345678' }}"
                  data-email="{{ $arrivalSection?->field('email') ?: 'info@mustermann-fewo.de' }}"></div>
              </div>
            </div>
          </div>
        </section>
      @break

      @case('contact')
        @php
          $k = $contactSection;
          $kPhone     = $k?->field('phone') ?? '';
          $kPhoneHref = $k?->field('phone_href') ?? '';
          $kEmail     = $k?->field('email') ?? '';
          $kMailHref  = $kEmail ? 'mailto:' . $kEmail : '';
        @endphp
        <section id="contact" class="contact section{{ $contactSection?->field('bg_alt') === '1' ? ' section--alt' : '' }}">
          <div class="container">
            <div class="section__header">
              <p class="section__eyebrow">{{ $k?->t('eyebrow') ?: ($isEn ? 'We look forward to your visit' : 'Wir freuen uns auf Sie') }}</p>
              <h2 class="section__title">{{ $k?->t('title') ?: ($isEn ? 'Contact & Enquiry' : 'Kontakt & Anfrage') }}</h2>
              <div class="section__divider"></div>
            </div>
            <div class="contact__layout">
              <div class="contact__text">
                @if ($k?->t('text_1'))
                  <p>{{ $k->t('text_1') }}</p>
                @endif
                @if ($k?->t('text_2'))
                  <p>{{ $k->t('text_2') }}</p>
                @endif
                @if ($kEmail)
                  <a href="{{ $kMailHref }}" class="btn btn--primary btn--large">
                    <span class="material-symbols-rounded">mail</span>
                    {{ $k?->t('btn_label') ?: ($isEn ? 'Send Email' : 'E-Mail schreiben') }}
                  </a>
                @endif
              </div>
              <div class="contact__card">
                <div class="contact__card-inner">
                  @if ($k?->t('card_label'))
                    <p class="contact__card-label">{{ $k->t('card_label') }}</p>
                  @endif
                  @if ($k?->field('card_name'))
                    <h3 class="contact__card-name">{{ $k->field('card_name') }}</h3>
                  @endif
                  <div class="contact__card-details">
                    @if ($k?->t('card_address'))
                      <div class="contact__card-row">
                        <span class="material-symbols-rounded">location_on</span>
                        <span>{{ $k->t('card_address') }}</span>
                      </div>
                    @endif
                    @if ($kPhone)
                      <div class="contact__card-row">
                        <span class="material-symbols-rounded">call</span>
                        <a href="tel:{{ $kPhoneHref }}">{{ $kPhone }}</a>
                      </div>
                    @endif
                    @if ($kEmail)
                      <div class="contact__card-row">
                        <span class="material-symbols-rounded">mail</span>
                        <a href="mailto:{{ $kEmail }}">{{ $kEmail }}</a>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      @break

    @endswitch
  @endforeach

  <!-- ===== FOOTER ===== -->
  <footer class="footer">
    <div class="footer__top">
      <div class="container footer__top-inner">
        <div class="footer__brand">
          @if ($footerSection?->field('brand_type') === 'logo' && $footerSection?->field('brand_logo'))
            @php
              $logoLight = $footerSection->field('brand_logo');
              $logoDark  = $footerSection->field('brand_logo_dark') ?: $logoLight;
              $logoAlt   = $footerSection->t('brand_name', 'Logo');
            @endphp
            <img src="{{ Storage::url($logoLight) }}" alt="{{ $logoAlt }}"
                 class="footer__brand-logo footer__brand-logo--light" />
            <img src="{{ Storage::url($logoDark) }}" alt="{{ $logoAlt }}"
                 class="footer__brand-logo footer__brand-logo--dark" />
          @else
            <p class="footer__brand-name">{{ $footerSection?->t('brand_name', 'Ferienwohnung') }}</p>
            <p class="footer__brand-sub">{{ $footerSection?->t('brand_sub') }}</p>
          @endif
        </div>
        <div class="footer__nav">
          <h4>{{ $ui['footer_nav'] }}</h4>
          <ul>
            @foreach ($orderedSections as $sec)
              @if (isset($navLabels[$sec->section_key]))
                <li><a href="{{ $navLabels[$sec->section_key]['href'] }}">{{ $navLabels[$sec->section_key]['label'] }}</a></li>
              @endif
            @endforeach
            @if (in_array('contact', $visibleSections))
              <li><a href="#contact">{{ $ui['nav_contact_footer'] }}</a></li>
            @endif
          </ul>
        </div>
        <div class="footer__contact">
          <h4>{{ $ui['footer_contact'] }}</h4>
          <address>
            @if ($footerSection?->field('contact_name'))
              <p>{{ $footerSection->field('contact_name') }}</p>
            @endif
            @if ($footerSection?->field('contact_street'))
              <p>{{ $footerSection->field('contact_street') }}</p>
            @endif
            @if ($footerSection?->field('contact_phone'))
              <p><a href="tel:{{ $footerSection->field('contact_phone_href') }}">{{ $footerSection->field('contact_phone') }}</a></p>
            @endif
            @if ($footerSection?->field('contact_email'))
              <p><a href="mailto:{{ $footerSection->field('contact_email') }}">{{ $footerSection->field('contact_email') }}</a></p>
            @endif
          </address>
        </div>
      </div>
    </div>

    <div class="footer__bottom">
      <div class="container footer__bottom-inner">
        <p>{{ $footerSection?->t('copyright', '© ' . date('Y') . ' – ' . ($isEn ? 'All rights reserved' : 'Alle Rechte vorbehalten')) }}</p>
        <nav class="footer__legal">
          <a href="{{ url('/impressum') }}">{{ $ui['impressum'] }}</a>
          <a href="{{ url('/datenschutz') }}">{{ $ui['datenschutz'] }}</a>
        </nav>
      </div>
    </div>
  </footer>

  <!-- ===== SCROLL-TO-TOP ===== -->
  <button id="scroll-top" class="scroll-top" aria-label="{{ $ui['scroll_top'] }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
  </button>

@endsection
