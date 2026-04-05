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
          @if (in_array('ueber-uns', $visibleSections))
            <li><a href="#ueber-uns">Die Wohnung</a></li>
          @endif
          @if (in_array('ausstattung', $visibleSections))
            <li><a href="#ausstattung">Ausstattung</a></li>
          @endif
          @if (in_array('galerie', $visibleSections))
            <li><a href="#galerie">Galerie</a></li>
          @endif
          @if (in_array('preise', $visibleSections))
            <li><a href="#preise">Preise</a></li>
          @endif
          @if (in_array('anreise', $visibleSections))
            <li><a href="#anreise">Anreise</a></li>
          @endif
          <li><a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a></li>
          @if (in_array('kontakt', $visibleSections))
            <li><a href="#kontakt" class="nav__cta">Anfragen</a></li>
          @endif
        </ul>
      </nav>
    </div>
  </header>

  <!-- ===== HERO ===== -->
  @if (in_array('hero', $visibleSections))
  <section id="hero" class="hero">
    <div class="hero__bg"></div>
    <div class="hero__overlay"></div>
    <div class="hero__content container">
      <p class="hero__eyebrow">{{ $heroSection?->field('eyebrow', 'Willkommen') ?? 'Willkommen' }}</p>
      <h1 class="hero__title">{{ $heroSection?->field('title', 'Ihr Urlaub auf Rügen') ?? 'Ihr Urlaub auf Rügen' }}</h1>
      <p class="hero__subtitle">{{ $heroSection?->field('subtitle', 'Ferienwohnung Heider in ruhiger Lage – nur 3 km vom Ostseebad Binz entfernt.') ?? 'Ferienwohnung Heider in ruhiger Lage – nur 3 km vom Ostseebad Binz entfernt.' }}</p>
      <div class="hero__actions">
        @if (in_array('galerie', $visibleSections))
          <a href="#galerie" class="btn btn--primary">Galerie ansehen</a>
        @endif
        @if (in_array('kontakt', $visibleSections))
          <a href="#kontakt" class="btn btn--outline">Jetzt anfragen</a>
        @endif
      </div>
    </div>
    <a href="#ueber-uns" class="hero__scroll" aria-label="Nach unten scrollen">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
    </a>
  </section>
  @endif

  <!-- ===== ÜBER UNS / DIE WOHNUNG ===== -->
  @if (in_array('ueber-uns', $visibleSections))
  @php $u = $aboutUsSection; @endphp
  <section id="ueber-uns" class="about section">
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
          @if (in_array('kontakt', $visibleSections))
            <a href="#kontakt" class="btn btn--primary">Jetzt anfragen</a>
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
  @if (in_array('ausstattung', $visibleSections))
  <section id="ausstattung" class="amenities section section--alt">
    <div class="container">
      <div class="section__header">
        <p class="section__eyebrow">Was wir bieten</p>
        <h2 class="section__title">Ausstattung</h2>
        <div class="section__divider"></div>
      </div>

      <div class="amenities__grid">
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">local_parking</span>
          <span class="amenity-item__label">Kostenfreier Stellparkplatz</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">wifi</span>
          <span class="amenity-item__label">W-LAN inklusive</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">radio</span>
          <span class="amenity-item__label">Radio mit CD-Player</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">tv</span>
          <span class="amenity-item__label">Satelliten-Fernsehen</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">cooking</span>
          <span class="amenity-item__label">Herd & Backofen</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">kitchen</span>
          <span class="amenity-item__label">Kühlschrank mit Tiefkühlfach</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">coffee_maker</span>
          <span class="amenity-item__label">Kaffeemaschine</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">breakfast_dining</span>
          <span class="amenity-item__label">Toaster</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">kettle</span>
          <span class="amenity-item__label">Wasserkocher</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">egg_alt</span>
          <span class="amenity-item__label">Eierkocher</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">bed</span>
          <span class="amenity-item__label">Bettwäsche inklusive</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">dry</span>
          <span class="amenity-item__label">Handtücher inklusive</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">door_front</span>
          <span class="amenity-item__label">Separater Eingang</span>
        </div>
        <div class="amenity-item">
          <span class="amenity-item__icon material-symbols-rounded">chair</span>
          <span class="amenity-item__label">Gemütliche Sitzecke</span>
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- ===== GALERIE ===== -->
  @if (in_array('galerie', $visibleSections))
  <section id="galerie" class="gallery section">
    <div class="container">
      <div class="section__header">
        <p class="section__eyebrow">Eindrücke</p>
        <h2 class="section__title">Galerie</h2>
        <div class="section__divider"></div>
      </div>

      <div class="gallery__grid" id="gallery-grid">
        <div class="gallery-item">
          <a href="/images/gallery/front.webp" data-fslightbox="gallery" data-caption="Hauseingang">
            <img src="/images/gallery/front.webp" alt="Hauseingang" loading="lazy" />
          </a>
          <div class="gallery-item__overlay"><span>Hauseingang</span></div>
        </div>
        <div class="gallery-item">
          <a href="/images/gallery/haus_02.webp" data-fslightbox="gallery" data-caption="Außenansicht">
            <img src="/images/gallery/haus_02.webp" alt="Außenansicht" loading="lazy" />
          </a>
          <div class="gallery-item__overlay"><span>Außenansicht</span></div>
        </div>
        <div class="gallery-item">
          <a href="/images/gallery/w1.webp" data-fslightbox="gallery" data-caption="Wohnbereich">
            <img src="/images/gallery/w1.webp" alt="Wohnbereich" loading="lazy" />
          </a>
          <div class="gallery-item__overlay"><span>Wohnbereich</span></div>
        </div>
        <div class="gallery-item">
          <a href="/images/gallery/w2.webp" data-fslightbox="gallery" data-caption="Schlafbereich">
            <img src="/images/gallery/w2.webp" alt="Schlafbereich" loading="lazy" />
          </a>
          <div class="gallery-item__overlay"><span>Schlafbereich</span></div>
        </div>
        <div class="gallery-item">
          <a href="/images/gallery/k.webp" data-fslightbox="gallery" data-caption="Küche">
            <img src="/images/gallery/k.webp" alt="Küche" loading="lazy" />
          </a>
          <div class="gallery-item__overlay"><span>Küche</span></div>
        </div>
        <div class="gallery-item">
          <a href="/images/gallery/t2.webp" data-fslightbox="gallery" data-caption="Sitzecke">
            <img src="/images/gallery/t2.webp" alt="Sitzecke" loading="lazy" />
          </a>
          <div class="gallery-item__overlay"><span>Sitzecke</span></div>
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- ===== PREISE & BELEGUNGSPLAN ===== -->
  @if (in_array('preise', $visibleSections))
  <section id="preise" class="pricing section section--alt">
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
  @if (in_array('anreise', $visibleSections))
  <section id="anreise" class="map-section section">
    <div class="container">
      <div class="section__header">
        <p class="section__eyebrow">So finden Sie uns</p>
        <h2 class="section__title">Anreise</h2>
        <div class="section__divider"></div>
      </div>

      <div class="map-section__layout">
        <div class="map-section__address">
          <h3>Ihre Unterkunft</h3>
          <address>
            <p class="address__name">Lolita Heider</p>
            <p>Serams 8A</p>
            <p>18528 Zirkow/Serams</p>
          </address>

          <div class="address__contact">
            <a href="tel:+493839331283" class="contact-link">
              <span class="contact-link__icon material-symbols-rounded">call</span>
              038393 31283
            </a>
            <a href="mailto:fewo.heider@gmail.com" class="contact-link">
              <span class="contact-link__icon material-symbols-rounded">mail</span>
              fewo.heider@gmail.com
            </a>
          </div>

          <div class="address__hints">
            <h4>Anreise-Tipps</h4>
            <ul>
              <li><span class="material-symbols-rounded">directions_car</span> Über A20 → Rügen-Brücke → B196 Richtung Binz</li>
              <li><span class="material-symbols-rounded">train</span> Zug bis Bergen auf Rügen, dann Bus Richtung Binz</li>
              <li><span class="material-symbols-rounded">flight</span> Flughafen Rostock-Laage (ca. 75 km)</li>
            </ul>
          </div>
        </div>

        <div class="map-section__map">
          <div id="map"></div>
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- ===== KONTAKT ===== -->
  @if (in_array('kontakt', $visibleSections))
  <section id="kontakt" class="contact section section--alt">
    <div class="container">
      <div class="section__header">
        <p class="section__eyebrow">Wir freuen uns auf Sie</p>
        <h2 class="section__title">Kontakt & Anfrage</h2>
        <div class="section__divider"></div>
      </div>

      <div class="contact__layout">
        <div class="contact__text">
          <p>
            Sie haben Fragen zur Ferienwohnung oder möchten einen Aufenthalt anfragen? Schreiben Sie uns einfach eine E-Mail – wir antworten schnell und unkompliziert.
          </p>
          <p>
            Teilen Sie uns gerne Ihre gewünschten Reisedaten sowie die Anzahl der Personen mit, dann prüfen wir die Verfügbarkeit für Sie.
          </p>
          <a href="mailto:fewo.heider@gmail.com?subject=Anfrage%20Ferienwohnung%20Heider&body=Hallo%20Frau%20Heider%2C%0A%0Aich%20interessiere%20mich%20f%C3%BCr%20Ihre%20Ferienwohnung.%0A%0AReisezeitraum%3A%20%0AAnzahl%20Personen%3A%20%0A%0AMit%20freundlichen%20Gr%C3%BC%C3%9Fen" class="btn btn--primary btn--large">
            <span class="material-symbols-rounded">mail</span> E-Mail schreiben
          </a>
        </div>

        <div class="contact__card">
          <div class="contact__card-inner">
            <p class="contact__card-label">Ihre Ansprechpartnerin</p>
            <h3 class="contact__card-name">Lolita Heider</h3>
            <div class="contact__card-details">
              <div class="contact__card-row">
                <span class="material-symbols-rounded">location_on</span>
                <span>Serams 8A, 18528 Zirkow/Serams</span>
              </div>
              <div class="contact__card-row">
                <span class="material-symbols-rounded">call</span>
                <a href="tel:+493839331283">038393 31283</a>
              </div>
              <div class="contact__card-row">
                <span class="material-symbols-rounded">mail</span>
                <a href="mailto:fewo.heider@gmail.com">fewo.heider@gmail.com</a>
              </div>
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
            @if (in_array('ueber-uns', $visibleSections))
              <li><a href="#ueber-uns">Die Wohnung</a></li>
            @endif
            @if (in_array('galerie', $visibleSections))
              <li><a href="#galerie">Galerie</a></li>
            @endif
            @if (in_array('preise', $visibleSections))
              <li><a href="#preise">Preise</a></li>
            @endif
            @if (in_array('kontakt', $visibleSections))
              <li><a href="#kontakt">Kontakt</a></li>
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
