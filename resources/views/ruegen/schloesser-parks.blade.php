@extends('layouts.pages')

@section('meta')
  <meta name="description" content="Schlösser & Parks auf Rügen – Jagdschloss Granitz, Putbus, Schlosspark Ralswiek und weitere fürstliche Anlagen." />
@endsection

@section('title', 'Schlösser & Parks auf Rügen')

@push('header-nav-links')
  <li><a href="{{ url('/') }}">Startseite</a></li>
  <li><a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a></li>
  <li><a href="{{ url('/#kontakt') }}" class="nav__cta">{{ ui_labels()['nav_contact'] }}</a></li>
@endpush

@section('content')

<div class="page-hero">
  <img src="/images/park_natur.jpg" alt="Schlösser und Parks auf Rügen" />
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content container">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">Startseite</a> <span>/</span>
      <a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a> <span>/</span>
      <span>Schlösser & Parks</span>
    </div>
    <h1>Schlösser & Parks auf <em>Rügen</em></h1>
  </div>
</div>

<section class="content">
  <div class="container">
    <div class="section-intro">
      <h2>Fürstliches Erbe der Insel</h2>
      <div class="divider"></div>
      <p>Rügen war über Jahrhunderte Residenzort norddeutscher Fürsten. Prächtige Schlösser, weitläufige Parkanlagen und klassizistische Stadtentwürfe zeugen noch heute von dieser glorreichen Vergangenheit.</p>
    </div>

    <div class="hero-feature">
      <div class="hero-feature__img">
        <span class="hero-feature__badge">Meistbesuchtes Museum MV</span>
        <img src="/images/redcube75-castle-4408581_1280.jpg" alt="Jagdschloss Granitz" loading="lazy" />
      </div>
      <div class="hero-feature__body">
        <p class="hero-feature__year">Erbaut 1837–1846</p>
        <h2 class="hero-feature__title">Jagdschloss Granitz</h2>
        <p class="hero-feature__text">Inmitten des Granitzer Waldes thront das Jagdschloss wie eine norditalienische Renaissance-Villa. Fürst Malte zu Putbus ließ es als repräsentatives Jagddomizil erbauen. Das Herzstück ist die spektakuläre gusseiserne Wendeltreppe im 38 m hohen Zentralturm – ein Meisterwerk des Eisenkunstgusses des 19. Jahrhunderts. Von der Turmspitze reicht der Blick über die gesamte Insel und die Ostsee.</p>
        <p class="hero-feature__text">Rund 150.000 Besucher jährlich machen es zum meistbesuchten Museum in Mecklenburg-Vorpommern.</p>
        <div class="hero-feature__facts">
          <div class="fact">
            <p class="fact__label">Entfernung</p>
            <p class="fact__value">ca. 10 km von Binz</p>
          </div>
          <div class="fact">
            <p class="fact__label">Turmhöhe</p>
            <p class="fact__value">38 m</p>
          </div>
          <div class="fact">
            <p class="fact__label">Stil</p>
            <p class="fact__value">Norditalienische Renaissance</p>
          </div>
          <div class="fact">
            <p class="fact__label">Besonderheit</p>
            <p class="fact__value">Gusseiserne Wendeltreppe</p>
          </div>
        </div>
      </div>
    </div>

    <div class="cards">

      <div class="card">
        <div class="card__img">
          <img src="/images/rasender_roland.jpg" alt="Putbus Circus" loading="lazy" />
        </div>
        <div class="card__body">
          <p class="card__year">Gegründet 1810</p>
          <h3 class="card__title">Putbus – Die Weiße Stadt</h3>
          <p class="card__text">Fürst Malte zu Putbus erschuf 1810 eine klassizistische Residenzstadt nach dem Vorbild von Bath. Der einzigartige Circus-Platz mit dem 21 m hohen Obelisk und der Schlosspark zählen zu den schönsten Stadtensembles Norddeutschlands.</p>
        </div>
      </div>

      <div class="card">
        <div class="card__img">
          <img src="/images/schlosspark_putbus.jpg" alt="Schlosspark Putbus" loading="lazy" />
        </div>
        <div class="card__body">
          <p class="card__year">Angelegt ab 1804</p>
          <h3 class="card__title">Schlosspark Putbus</h3>
          <p class="card__text">Einer der frühesten englischen Landschaftsparks in Norddeutschland. Weitläufige Rasenflächen, alte Bäume, Teiche und verschlungene Wege laden zu Spaziergängen ein – ein ruhiger Gegenpol zum Badetreiben an der Küste.</p>
        </div>
      </div>

      <div class="card">
        <div class="card__img">
          <img src="/images/schlosshotel_ralswiek.jpg" alt="Schloss Ralswiek" loading="lazy" />
        </div>
        <div class="card__body">
          <p class="card__year">Erbaut 1891</p>
          <h3 class="card__title">Schloss Ralswiek</h3>
          <p class="card__text">Das Schloss am Großen Jasmunder Bodden ist heute als Bühne der Störtebeker Festspiele weltbekannt. Die erhöhte Lage mit Blick auf den Bodden schafft eine einzigartige Kulisse für das größte Open-Air-Theater Deutschlands.</p>
        </div>
      </div>

      <div class="card">
        <div class="card__img">
          <img src="/images/kreidefelsen.jpg" alt="Nationalpark Jasmund" loading="lazy" />
        </div>
        <div class="card__body">
          <p class="card__year">UNESCO-Weltnaturerbe</p>
          <h3 class="card__title">Nationalpark Jasmund</h3>
          <p class="card__text">Rügens kleinster, aber berühmtester Nationalpark beherbergt die weißen Kreidefelsen und uralte Buchenwälder. Seit 2011 UNESCO-Weltnaturerbe – ein einzigartiges Naturparadies an der Ostseeküste.</p>
        </div>
      </div>

      <div class="card">
        <div class="card__img">
          <img src="/images/binz.jpg" alt="Kurpark Binz" loading="lazy" />
        </div>
        <div class="card__body">
          <p class="card__year">Historischer Kurpark</p>
          <h3 class="card__title">Kurpark Binz</h3>
          <p class="card__text">Direkt hinter der Strandpromenade erstreckt sich der gepflegte Kurpark von Binz mit altem Baumbestand, Rosengarten und Konzertmuschel. Perfekt für einen entspannten Morgenspaziergang oder eine Pause zwischen Strand und Erkundung.</p>
        </div>
      </div>

      <div class="card">
        <div class="card__img">
          <img src="/images/bodden.jpg" alt="Boddenlandschaft Rügen" loading="lazy" />
        </div>
        <div class="card__body">
          <p class="card__year">Naturschutzgebiet</p>
          <h3 class="card__title">Boddenlandschaft & Naturparks</h3>
          <p class="card__text">Die weitläufigen Boddengewässer rund um Rügen sind Heimat seltener Vogelarten. Im Naturpark Vorpommersche Boddenlandschaft rasten alljährlich tausende Kraniche – ein unvergessliches Naturschauspiel im Herbst.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<div class="cta-strip">
  <h2>Geschichte erleben auf Rügen</h2>
  <p>Buchen Sie Ihre Ferienwohnung und erkunden Sie Rügens fürstliches Erbe auf eigene Faust.</p>
  <a href="{{ url('/#kontakt') }}" class="btn btn--white"><span class="material-symbols-rounded">mail</span> Jetzt anfragen</a>
  <a href="{{ url('/ruegen-erleben') }}" class="btn btn--outline">Alle Kategorien</a>
</div>

@endsection

@section('footer')
  <p>© {{ date('Y') }} Musterferienwohnung</p>
  <nav class="footer__legal">
    <a href="{{ url('/impressum') }}">Impressum</a>
    <a href="{{ url('/datenschutz') }}">Datenschutz</a>
  </nav>
@endsection