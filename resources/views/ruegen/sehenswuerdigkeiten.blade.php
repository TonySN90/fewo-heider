@extends('layouts.pages')

@section('meta')
  <meta name="description" content="Sehenswürdigkeiten auf Rügen – Königsstuhl, Kreidefelsen, Störtebeker Festspiele, Kap Arkona und mehr." />
@endsection

@section('title', 'Sehenswürdigkeiten auf Rügen')

@section('nav')
  <a href="{{ url('/') }}">Startseite</a>
  <a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a>
  <a href="{{ url('/#kontakt') }}">Anfragen</a>
@endsection

@section('content')

<div class="page-hero">
  <img src="/images/kreidefelsen_von_see.jpg" alt="Sehenswürdigkeiten Rügen" />
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content container">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">Startseite</a> <span>/</span>
      <a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a> <span>/</span>
      <span>Sehenswürdigkeiten</span>
    </div>
    <h1>Sehenswürdigkeiten auf <em>Rügen</em></h1>
  </div>
</div>

<section class="content">
  <div class="container">
    <div class="section-intro">
      <h2>Highlights einer außergewöhnlichen Insel</h2>
      <div class="divider"></div>
      <p>Von Weltnaturerbe bis Open-Air-Theater – Rügen begeistert mit einer einzigartigen Mischung aus Naturwundern, Geschichte und Kultur.</p>
    </div>

    <div class="feature">
      <div class="feature__img">
        <img src="/images/koenigsstuhl.jpg" alt="Königsstuhl Kreidefelsen" loading="lazy" />
      </div>
      <div class="feature__body">
        <p class="feature__category">Naturdenkmal · Nationalpark Jasmund</p>
        <h2 class="feature__title">Der Königsstuhl</h2>
        <p class="feature__text">Mit 118 m ist der Königsstuhl der höchste Kreidefelsen Deutschlands und das bekannteste Wahrzeichen Rügens. Gemalt von Caspar David Friedrich, inspiriert von der Romantik – der Anblick der schneeweißen Felswände über dem tiefen Blau der Ostsee ist unvergesslich. Das Nationalpark-Zentrum Königsstuhl bietet Ausstellungen zur Entstehungsgeschichte der Kreide.</p>
        <p class="feature__text">Seit 2023 ergänzt der „Königsweg" – eine 185 m lange Aussichtsplattform, die scheinbar über den Felsen schwebt – das Erlebnis.</p>
        <div class="feature__info">
          <span class="info-item"><span class="material-symbols-rounded">schedule</span> tägl. 10–17 Uhr</span>
          <span class="info-item"><span class="material-symbols-rounded">payments</span> ab 12 € Erw. / 6 € Kinder</span>
          <span class="info-item"><span class="material-symbols-rounded">location_on</span> Stubbenkammer, 18546 Sassnitz</span>
        </div>
      </div>
    </div>

    <div class="feature feature--rev">
      <div class="feature__img">
        <img src="/images/adler.jpg" alt="Störtebeker Festspiele" loading="lazy" />
      </div>
      <div class="feature__body">
        <p class="feature__category">Kultur · Open-Air-Theater</p>
        <h2 class="feature__title">Störtebeker Festspiele</h2>
        <p class="feature__text">Deutschlands größtes Open-Air-Theater findet seit 1993 alljährlich in Ralswiek am Ufer des Großen Jasmunder Boddens statt. Die spektakulären Inszenierungen rund um den legendären Piraten Klaus Störtebeker verbinden atemberaubende Bühnenbilder mit Livemusik, Pyrotechnik und Kampfchoreografien.</p>
        <p class="feature__text">Bis zu 8.500 Zuschauer erleben unter freiem Himmel ein einzigartiges Theaterspektakel – ein Highlight für die ganze Familie.</p>
        <div class="feature__info">
          <span class="info-item"><span class="material-symbols-rounded">calendar_month</span> Ende Juni – Anfang September</span>
          <span class="info-item"><span class="material-symbols-rounded">location_on</span> Ralswiek am Bodden</span>
        </div>
      </div>
    </div>

    <div class="feature">
      <div class="feature__img">
        <img src="/images/kap_arkona.jpg" alt="Leuchttürme Kap Arkona" loading="lazy" />
      </div>
      <div class="feature__body">
        <p class="feature__category">Geschichte · Nordspitze Rügens</p>
        <h2 class="feature__title">Leuchttürme Kap Arkona</h2>
        <p class="feature__text">Am nördlichsten Punkt Rügens stehen drei historische Türme nebeneinander: Der Schinkelturm (1827) aus rotem Backstein – ein Meisterwerk des klassizistischen Ingenieurbaus – dient heute als Standesamt. Der neuere Leuchtturm (1902) sendet sein Feuer 44 km weit über die Ostsee. Der Peilturm (1927) bietet von seiner Plattform einen atemberaubenden Panoramablick.</p>
        <div class="feature__info">
          <span class="info-item"><span class="material-symbols-rounded">location_on</span> Kap Arkona, Putgarten</span>
          <span class="info-item"><span class="material-symbols-rounded">directions_car</span> ca. 45 km von Binz</span>
        </div>
      </div>
    </div>

    <div class="mini-grid">
      <div class="mini-card">
        <span class="mini-card__icon material-symbols-rounded">account_balance</span>
        <h3 class="mini-card__title">Jaromarsburg</h3>
        <p class="mini-card__text">Slawische Tempelburg aus dem 6. Jahrhundert am Kap Arkona – Kultstätte des Gottes Svantevit. 1168 von dänischen Kreuzrittern zerstört, heute beeindruckende Überreste in dramatischer Küstenlage.</p>
      </div>
      <div class="mini-card">
        <span class="mini-card__icon material-symbols-rounded">anchor</span>
        <h3 class="mini-card__title">U-Boot HMS Otus</h3>
        <p class="mini-card__text">Begehbares britisches U-Boot im Hafen Sassnitz. Authentische Ausstattung, enge Gänge und Kammern sowie Führungen zur Geschichte des Falklandkriegs machen es zu einem einzigartigen Erlebnis.</p>
      </div>
      <div class="mini-card">
        <span class="mini-card__icon material-symbols-rounded">train</span>
        <h3 class="mini-card__title">Rasender Roland</h3>
        <p class="mini-card__text">Die historische Schmalspurbahn „Rügensche Bäderbahn" fährt seit über 100 Jahren auf 24 km zwischen Putbus und Göhren – ein nostalgisches Erlebnis mit Dampflok-Charme und herrlichen Landschaftspanoramen.</p>
      </div>
      <div class="mini-card">
        <span class="mini-card__icon material-symbols-rounded">waves</span>
        <h3 class="mini-card__title">Seebrücken</h3>
        <p class="mini-card__text">Die Seebrücken in Binz (370 m) und Sellin (394 m) sind Wahrzeichen ihrer Seebäder. Mit Restaurants, Tauchgondeln und Bootsanlegern laden sie zum Flanieren über das Meer ein.</p>
      </div>
      <div class="mini-card">
        <span class="mini-card__icon material-symbols-rounded">location_city</span>
        <h3 class="mini-card__title">Prora – Koloss von Rügen</h3>
        <p class="mini-card__text">Der 4,5 km lange KdF-Baukomplex aus der NS-Zeit ist ein einzigartiges Architektur- und Geschichtsdenkmal. Heute beherbergt er Museen, moderne Apartments und eine Jugendherberge direkt am Strand.</p>
      </div>
      <div class="mini-card">
        <span class="mini-card__icon material-symbols-rounded">forest</span>
        <h3 class="mini-card__title">UNESCO-Buchenwälder</h3>
        <p class="mini-card__text">Die alten Buchenwälder des Nationalparks Jasmund gehören seit 2011 zum UNESCO-Weltnaturerbe. Majestätische Bäume, deren Wurzeln bis an die Kreideküste reichen – ein einzigartiges Naturerlebnis.</p>
      </div>
    </div>
  </div>
</section>

<div class="cta-strip">
  <h2>Rügens Highlights erleben</h2>
  <p>Buchen Sie Ihre Unterkunft und erkunden Sie die Sehenswürdigkeiten der Insel auf eigene Faust.</p>
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