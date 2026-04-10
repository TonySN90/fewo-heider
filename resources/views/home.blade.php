@extends('layouts.app')

@section('meta')
  <meta name="description" content="Ferienwohnung Heider auf Rügen – gemütliche 30 m² Wohnung nahe Ostseebad Binz. Jetzt anfragen!" />
@endsection

@section('title', 'Ferienwohnung Heider – Rügen')

@section('content')

  <!-- ===== HEADER / NAVIGATION ===== -->
  <header id="header">
    <div class="header__inner container">
      <a href="#hero" class="header__logo">
        <span class="header__logo-text">Ferienwohnung<strong> Heider</strong></span>
        <span class="header__logo-sub">Rügen · Serams</span>
      </a>

      <button class="header__hamburger" id="hamburger" aria-label="Menü öffnen" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <nav class="header__nav" id="main-nav">
        <ul>
          @if (in_array('about', $visibleSections))
            <li><a href="#about">Die Wohnung</a></li>
          @endif
          @if (in_array('amenities', $visibleSections))
            <li><a href="#amenities">Ausstattung</a></li>
          @endif
          @if (in_array('gallery', $visibleSections))
            <li><a href="#gallery">Galerie</a></li>
          @endif
          @if (in_array('pricing', $visibleSections))
            <li><a href="#pricing">Preise</a></li>
          @endif
          @if (in_array('arrival', $visibleSections))
            <li><a href="#arrival">Anreise</a></li>
          @endif
          @foreach ($pageGroups as $group)
            <li><a href="{{ url('/' . $group->slug) }}">{{ $group->nav_label }}</a></li>
          @endforeach
          @if (in_array('contact', $visibleSections))
            <li><a href="#contact" class="nav__cta">Anfragen</a></li>
          @endif
        </ul>
      </nav>
    </div>
  </header>

  <!-- ===== HERO ===== -->
  @if (in_array('hero', $visibleSections))
  <section id="hero" class="hero">
    <div class="hero__bg" @if($heroSection?->field('cover_image')) style="background-image:url('{{ Storage::url($heroSection->field('cover_image')) }}')" @endif></div>
    <div class="hero__overlay"></div>
    <div class="hero__content container">
      <p class="hero__eyebrow">{{ $heroSection?->field('eyebrow', 'Willkommen') ?? 'Willkommen' }}</p>
      <h1 class="hero__title">{{ $heroSection?->field('title', 'Ihr Urlaub auf Rügen') ?? 'Ihr Urlaub auf Rügen' }}</h1>
      <p class="hero__subtitle">{{ $heroSection?->field('subtitle', 'Ferienwohnung Heider in ruhiger Lage – nur 3 km vom Ostseebad Binz entfernt.') ?? 'Ferienwohnung Heider in ruhiger Lage – nur 3 km vom Ostseebad Binz entfernt.' }}</p>
      <div class="hero__actions">
        @if (in_array('gallery', $visibleSections))
          <a href="#gallery" class="btn btn--primary">Galerie ansehen</a>
        @endif
        @if (in_array('contact', $visibleSections))
          <a href="#contact" class="btn btn--outline">Jetzt anfragen</a>
        @endif
      </div>
    </div>
    <a href="#about" class="hero__scroll" aria-label="Nach unten scrollen">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
    </a>
  </section>
  @endif

  <!-- ===== ÜBER UNS / DIE WOHNUNG ===== -->
  @if (in_array('about', $visibleSections))
  @php $u = $aboutSection; @endphp
  <section id="about" class="about section">
    <div class="container">
      <div class="section__header">
        <p class="section__eyebrow">{{ $u?->field('eyebrow', 'Willkommen') ?? 'Willkommen' }}</p>
        <h2 class="section__title">{{ $u?->field('title', 'Ferienwohnung Heider') ?? 'Ferienwohnung Heider' }}</h2>
        <div class="section__divider"></div>
      </div>

      <div class="about__grid">
        <div class="about__text">
          @if ($u?->field('text_1'))
            <p class="about__intro">{{ $u->field('text_1') }}</p>
          @endif
          @if ($u?->field('text_2'))
            <p>{{ $u->field('text_2') }}</p>
          @endif
          @if ($u?->field('text_3'))
            <p>{{ $u->field('text_3') }}</p>
          @endif
          @if (in_array('contact', $visibleSections))
            <a href="#contact" class="btn btn--primary">Jetzt anfragen</a>
          @endif
        </div>

        <div class="about__highlights">
          @for ($i = 1; $i <= 6; $i++)
            @php
              $icon    = $u?->field("card_{$i}_icon");
              $heading = $u?->field("card_{$i}_heading");
              $text    = $u?->field("card_{$i}_text");
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
  @endif

  <!-- ===== AUSSTATTUNG ===== -->
  @if (in_array('amenities', $visibleSections))
  @php $am = $amenitiesSection; @endphp
  <section id="amenities" class="amenities section section--alt">
    <div class="container">
      <div class="section__header">
        <p class="section__eyebrow">{{ $am?->field('eyebrow', 'Was wir bieten') ?? 'Was wir bieten' }}</p>
        <h2 class="section__title">{{ $am?->field('title', 'Ausstattung') ?? 'Ausstattung' }}</h2>
        <div class="section__divider"></div>
      </div>

      <div class="amenities__grid">
        @if ($am)
          @for ($i = 1; $i <= 50; $i++)
            @php $icon = $am->field("amenity_{$i}_icon"); $label = $am->field("amenity_{$i}_label"); @endphp
            @if ($icon && $label)
              <div class="amenity-item">
                <span class="amenity-item__icon material-symbols-rounded">{{ $icon }}</span>
                <span class="amenity-item__label">{{ $label }}</span>
              </div>
            @elseif (!$icon && !$label)
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
  @endif

  <!-- ===== GALERIE ===== -->
  @if (in_array('gallery', $visibleSections))
  @php
    $visibleImgs = $galleryImages->take(6);
    $hiddenImgs  = $galleryImages->skip(6);
  @endphp
  <section id="gallery" class="gallery section">
    <div class="container">
      <div class="section__header">
        <p class="section__eyebrow">{{ $gallerySection?->field('eyebrow', 'Eindrücke') ?? 'Eindrücke' }}</p>
        <h2 class="section__title">{{ $gallerySection?->field('title', 'Galerie') ?? 'Galerie' }}</h2>
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

        {{-- Versteckte Bilder (ab Bild 6) – nur als <a> für Lightbox, kein img-Request --}}
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
            Alle {{ $galleryImages->count() }} Bilder anschauen
          </button>
        </div>
      @endif
    </div>
  </section>
  @endif

  <!-- ===== PREISE & BELEGUNGSPLAN ===== -->
  @if (in_array('pricing', $visibleSections))
  <section id="pricing" class="pricing section section--alt">
    <div class="container">
      <div class="section__header">
        <p class="section__eyebrow">Übersicht</p>
        <h2 class="section__title">Preise & Verfügbarkeit</h2>
        <div class="section__divider"></div>
      </div>

      <div class="pricing__layout">
        <!-- Preistabelle -->
        <div class="pricing__table-wrap">
          <h3 class="pricing__subtitle">Preistabelle <span id="pricing-year">{{ now()->year }}</span></h3>
          <div class="pricing__table-scroll">
          <table class="pricing__table">
            <thead>
              <tr>
                <th>Saison</th>
                <th>Zeitraum</th>
                <th>pro Nacht</th>
                <th>Mindestaufenthalt</th>
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

        <!-- Belegungskalender -->
        <div class="pricing__calendar-wrap">
          <div class="pricing__subtitle-row">
            <h3 class="pricing__subtitle">Belegungsplan</h3>
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
            <span class="legend-item legend-item--free">Frei</span>
            <span class="legend-item legend-item--booked">Belegt</span>
          </div>
          <div id="booking-calendar" class="booking-calendar"></div>
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- ===== ANREISE / KARTE ===== -->
  @if (in_array('arrival', $visibleSections))
  <section id="arrival" class="map-section section">
    <div class="container">
      <div class="section__header">
        <p class="section__eyebrow">{{ $arrivalSection?->field('eyebrow') }}</p>
        <h2 class="section__title">{{ $arrivalSection?->field('title') }}</h2>
        <div class="section__divider"></div>
      </div>

      <div class="map-section__layout">
        <div class="map-section__address">
          <h3>{{ $arrivalSection?->field('address_subtitle') ?: 'Ihre Unterkunft' }}</h3>
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
              $text = $arrivalSection?->field("hint_{$i}_text");
              if ($icon || $text) {
                $arrivalHints[] = ['icon' => $icon, 'text' => $text];
              }
            }
          @endphp
          @if (!empty($arrivalHints))
          <div class="address__hints">
            <h4>{{ $arrivalSection?->field('hints_title', 'Anreise-Tipps') ?? 'Anreise-Tipps' }}</h4>
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
            data-lng="{{ $arrivalSection?->field('map_lng') ?: '13.5632' }}"></div>
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- ===== KONTAKT ===== -->
  @if (in_array('contact', $visibleSections))
  <section id="contact" class="contact section section--alt">
    <div class="container">
      @php
        $k = $contactSection;
        $kPhone     = $k?->field('phone') ?? '';
        $kPhoneHref = $k?->field('phone_href') ?? '';
        $kEmail     = $k?->field('email') ?? '';
        $kMailHref  = $kEmail ? 'mailto:' . $kEmail : '';
      @endphp

      <div class="section__header">
        <p class="section__eyebrow">{{ $k?->field('eyebrow') ?: 'Wir freuen uns auf Sie' }}</p>
        <h2 class="section__title">{{ $k?->field('title') ?: 'Kontakt & Anfrage' }}</h2>
        <div class="section__divider"></div>
      </div>

      <div class="contact__layout">
        <div class="contact__text">
          @if ($k?->field('text_1'))
            <p>{{ $k->field('text_1') }}</p>
          @endif
          @if ($k?->field('text_2'))
            <p>{{ $k->field('text_2') }}</p>
          @endif
          @if ($kEmail)
            <a href="{{ $kMailHref }}" class="btn btn--primary btn--large">
              <span class="material-symbols-rounded">mail</span>
              {{ $k?->field('btn_label') ?: 'E-Mail schreiben' }}
            </a>
          @endif
        </div>

        <div class="contact__card">
          <div class="contact__card-inner">
            @if ($k?->field('card_label'))
              <p class="contact__card-label">{{ $k->field('card_label') }}</p>
            @endif
            @if ($k?->field('card_name'))
              <h3 class="contact__card-name">{{ $k->field('card_name') }}</h3>
            @endif
            <div class="contact__card-details">
              @if ($k?->field('card_address'))
                <div class="contact__card-row">
                  <span class="material-symbols-rounded">location_on</span>
                  <span>{{ $k->field('card_address') }}</span>
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
  @endif

  <!-- ===== FOOTER ===== -->
  <footer class="footer">
    <div class="footer__top">
      <div class="container footer__top-inner">
        <div class="footer__brand">
          <p class="footer__brand-name">Ferienwohnung Heider</p>
          <p class="footer__brand-sub">Rügen · Serams · Ostseebad Binz</p>
        </div>
        <div class="footer__nav">
          <h4>Navigation</h4>
          <ul>
            @if (in_array('about', $visibleSections))
              <li><a href="#about">Die Wohnung</a></li>
            @endif
            @if (in_array('gallery', $visibleSections))
              <li><a href="#gallery">Galerie</a></li>
            @endif
            @if (in_array('pricing', $visibleSections))
              <li><a href="#pricing">Preise</a></li>
            @endif
            @if (in_array('contact', $visibleSections))
              <li><a href="#contact">Kontakt</a></li>
            @endif
          </ul>
        </div>
        <div class="footer__contact">
          <h4>Kontakt</h4>
          <address>
            <p>Lolita Heider</p>
            <p>Serams 8A, 18528 Zirkow/Serams</p>
            <p><a href="tel:+493839331283">038393 31283</a></p>
            <p><a href="mailto:fewo.heider@gmail.com">fewo.heider@gmail.com</a></p>
          </address>
        </div>
      </div>
    </div>

    <div class="footer__bottom">
      <div class="container footer__bottom-inner">
        <p>© 2026 Ferienwohnung Heider – Alle Rechte vorbehalten</p>
        <nav class="footer__legal">
          <a href="{{ url('/impressum') }}">Impressum</a>
          <a href="{{ url('/datenschutz') }}">Datenschutz</a>
        </nav>
      </div>
    </div>
  </footer>

  <!-- ===== SCROLL-TO-TOP ===== -->
  <button id="scroll-top" class="scroll-top" aria-label="Nach oben scrollen">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
  </button>

@endsection
