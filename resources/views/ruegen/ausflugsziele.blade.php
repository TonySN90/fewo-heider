@extends('layouts.pages')

@section('meta')
  <meta name="description" content="Ausflugsziele auf Rügen – von Binz und Sellin bis Kap Arkona und Sassnitz. Die schönsten Orte der Insel entdecken." />
@endsection

@section('title', 'Ausflugsziele auf Rügen')

@push('header-nav-links')
  <li><a href="{{ url('/') }}">Startseite</a></li>
  <li><a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a></li>
  <li><a href="{{ url('/#kontakt') }}" class="nav__cta">{{ ui_labels()['nav_contact'] }}</a></li>
@endpush

@section('content')

<div class="page-hero">
  <img src="/images/sellin.jpg" alt="Ausflugsziele auf Rügen" />
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content container">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">Startseite</a> <span>/</span>
      <a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a> <span>/</span>
      <span>Ausflugsziele</span>
    </div>
    <h1>Ausflugsziele auf <em>Rügen</em></h1>
  </div>
</div>

<section class="content">
  <div class="container">
    <div class="content__intro">
      <h2>Die schönsten Orte der Insel</h2>
      <div class="divider"></div>
      <p>Rügen begeistert mit einer Vielfalt an Ausflugszielen – elegante Seebäder, wilde Küstenabschnitte und malerische Fischerdörfer liegen nah beieinander.</p>
    </div>

    <div class="place-list">

      <div class="place">
        <div class="place__img">
          <img src="/images/binz.jpg" alt="Ostseebad Binz" loading="lazy" />
        </div>
        <div class="place__body">
          <p class="place__dist"><span class="material-symbols-rounded" style="font-size:0.9rem;">location_on</span> ca. 3 km entfernt</p>
          <h2 class="place__title">Ostseebad Binz</h2>
          <p class="place__text">Binz ist das größte und bekannteste Seebad Rügens. Die 3,2 km lange, lindengesäumte Strandpromenade mit ihren weißen Bädervillen im Stil der Jahrhundertwende verbreitet ein unvergleichliches Flair. Die 370 m lange Seebrücke mit Restaurant und Tauchgondel lädt zum Bummeln und Staunen ein. Zahlreiche Restaurants, Cafés und Boutiquen machen Binz zum geselligen Mittelpunkt der Insel.</p>
          <div class="place__tags">
            <span class="tag">Strandpromenade</span>
            <span class="tag">Seebrücke</span>
            <span class="tag">Bäderarchitektur</span>
            <span class="tag">Gastronomie</span>
          </div>
        </div>
      </div>

      <div class="place">
        <div class="place__img">
          <img src="/images/sellin.jpg" alt="Ostseebad Sellin" loading="lazy" />
        </div>
        <div class="place__body">
          <p class="place__dist"><span class="material-symbols-rounded" style="font-size:0.9rem;">location_on</span> ca. 15 km entfernt</p>
          <h2 class="place__title">Ostseebad Sellin</h2>
          <p class="place__text">Sellin besticht durch sein elegantes Ambiente und die 394 m lange Seebrücke – die längste auf Rügen. Mit Restaurant, Tauchgondel und Bootsanleger ist sie ein Erlebnis für sich. Die typische weiße Bäderarchitektur, hübsche Villen und der weitläufige Strand machen Sellin zu einem der schönsten Seebäder der Insel.</p>
          <div class="place__tags">
            <span class="tag">Längste Seebrücke Rügens</span>
            <span class="tag">Bäderarchitektur</span>
            <span class="tag">Strand</span>
          </div>
        </div>
      </div>

      <div class="place">
        <div class="place__img">
          <img src="/images/kap_arkona.jpg" alt="Kap Arkona" loading="lazy" />
        </div>
        <div class="place__body">
          <p class="place__dist"><span class="material-symbols-rounded" style="font-size:0.9rem;">location_on</span> ca. 45 km entfernt</p>
          <h2 class="place__title">Kap Arkona</h2>
          <p class="place__text">Die nördlichste Spitze Rügens ist ein dramatisches Naturspektakel: 45 m hohe Kreidefelsen fallen steil ins Meer. Drei historische Leuchttürme – darunter der von Schinkel entworfene Backsteinturm von 1827 – überragen die Landschaft. Die benachbarte slawische Tempelburg Jaromarsburg aus dem 6. Jahrhundert erzählt von der vorchristlichen Geschichte der Insel.</p>
          <div class="place__tags">
            <span class="tag">Leuchttürme</span>
            <span class="tag">Kreidefelsen</span>
            <span class="tag">Jaromarsburg</span>
            <span class="tag">Nordspitze Rügens</span>
          </div>
        </div>
      </div>

      <div class="place">
        <div class="place__img">
          <img src="/images/sassnitz_leuchtturm.jpg" alt="Sassnitz Hafen" loading="lazy" />
        </div>
        <div class="place__body">
          <p class="place__dist"><span class="material-symbols-rounded" style="font-size:0.9rem;">location_on</span> ca. 25 km entfernt</p>
          <h2 class="place__title">Sassnitz – Hafenstadt mit Flair</h2>
          <p class="place__text">Sassnitz ist die nördlichste Stadt Rügens und bietet mit ihrem historischen Fischerhafen und dem Stadthafen echtes maritimes Flair. Eine 500 m lange Steinmole mit Leuchtturm lädt zum Spaziergang ein. Fähren in die skandinavischen Länder legen hier ab, und das U-Boot-Museum HMS Otus sorgt für ein besonderes Erlebnis.</p>
          <div class="place__tags">
            <span class="tag">Fischerhafen</span>
            <span class="tag">U-Boot-Museum</span>
            <span class="tag">Fähren nach Schweden</span>
          </div>
        </div>
      </div>

      <div class="place">
        <div class="place__img">
          <img src="/images/putbus_see.png" alt="Putbus Circus" loading="lazy" />
        </div>
        <div class="place__body">
          <p class="place__dist"><span class="material-symbols-rounded" style="font-size:0.9rem;">location_on</span> ca. 20 km entfernt</p>
          <h2 class="place__title">Putbus – Die Weiße Stadt</h2>
          <p class="place__text">Putbus wurde 1810 von Fürst Malte zu Putbus als klassizistische Residenzstadt geplant. Das Herzstück ist der einzigartige Circus – ein kreisrunder Platz mit weißen Häusern, von dem acht Alleen sternförmig abgehen. Ein 21 m hoher Obelisk mit Fürstenkrone krönt das Zentrum. Der weitläufige Schlosspark lädt zu Spaziergängen ein.</p>
          <div class="place__tags">
            <span class="tag">Klassizismus</span>
            <span class="tag">Circus Platz</span>
            <span class="tag">Schlosspark</span>
            <span class="tag">Rasender Roland</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<div class="cta-strip">
  <h2>All das direkt vor der Haustür</h2>
  <p>Unsere Ferienwohnung liegt zentral – perfekt für Tagesausflüge zu allen Ausflugszielen Rügens.</p>
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