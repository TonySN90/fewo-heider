@extends('layouts.pages')

@section('meta')
  <meta name="description" content="Wandern auf Rügen – die schönsten Wanderwege entlang der Kreideküste, durch Buchenwälder und am Hochufer des Nationalparks Jasmund." />
@endsection

@section('title', 'Wandern auf Rügen')

@section('nav')
  <a href="{{ url('/') }}">Startseite</a>
  <a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a>
  <a href="{{ url('/#kontakt') }}">Anfragen</a>
@endsection

@section('content')

<div class="page-hero">
  <img src="/images/wandern2.jpg" alt="Wandern auf Rügen" />
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content container">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">Startseite</a> <span>/</span>
      <a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a> <span>/</span>
      <span>Wandern</span>
    </div>
    <h1>Wandern auf <em>Rügen</em></h1>
  </div>
</div>

<section class="content">
  <div class="container">
    <div class="content__intro">
      <h2>Die schönsten Wanderwege der Insel</h2>
      <div class="divider"></div>
      <p>Von der dramatischen Kreideküste bis in stille Buchenwälder – Rügen bietet Wanderungen für alle Schwierigkeitsgrade und jede Jahreszeit.</p>
    </div>

    <div class="cards">

      <div class="card">
        <div class="card__img">
          <img src="/images/hochufer_jasmund.jpg" alt="Hochuferweg Jasmund" loading="lazy" />
        </div>
        <div class="card__body">
          <div class="card__meta">
            <span class="badge badge--green">Leicht – Moderat</span>
            <span class="badge badge--blue">12,6 km · ca. 3,5 Std.</span>
          </div>
          <h3 class="card__title">Hochuferweg Jasmund</h3>
          <p class="card__text">Einer der schönsten Wanderwege Deutschlands führt durch den Nationalpark Jasmund entlang der weißen Kreidefelsen. Der Weg verbindet Lohme und Sassnitz und bietet immer wieder spektakuläre Ausblicke auf die Ostsee. UNESCO-geschützte Buchenwälder säumen den Pfad.</p>
          <div class="card__highlights">
            <h4>Highlights</h4>
            <ul>
              <li>Königsstuhl (118 m) – bekanntester Kreidefelsen Rügens</li>
              <li>Wissower Klinken & Victoriasicht</li>
              <li>Ernst-Moritz-Arndt-Sicht</li>
              <li>UNESCO-Buchenwälder</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card__img">
          <img src="/images/schmachter_see.jpg" alt="Schmachter See Rundweg" loading="lazy" />
        </div>
        <div class="card__body">
          <div class="card__meta">
            <span class="badge badge--green">Leicht</span>
            <span class="badge badge--blue">12–13 km · ca. 3 Std.</span>
          </div>
          <h3 class="card__title">Schmachter See Rundweg</h3>
          <p class="card__text">Eine herrliche Rundwanderung ab Binz rund um den naturgeschützten Schmachter See. Der Weg führt durch ruhige Wälder und Felder, vorbei am Jagdschloss Granitz und durch den romantischen Kurpark von Binz. Ideal für Familien und weniger geübte Wanderer.</p>
          <div class="card__highlights">
            <h4>Highlights</h4>
            <ul>
              <li>Jagdschloss Granitz mit Aussichtsturm</li>
              <li>Naturschutzgebiet Schmachter See</li>
              <li>Kurpark Binz mit Rosengarten</li>
              <li>Strandabschnitte bei Binz</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card__img">
          <img src="/images/juliusruh.jpg" alt="Schaabe Strandwanderung" loading="lazy" />
        </div>
        <div class="card__body">
          <div class="card__meta">
            <span class="badge badge--green">Leicht</span>
            <span class="badge badge--blue">ca. 12 km · ca. 3 Std.</span>
          </div>
          <h3 class="card__title">Schaabe Strandwanderung</h3>
          <p class="card__text">Entlang des längsten Strandes Rügens – der Schaabe – verläuft dieser traumhafte Küstenwanderweg zwischen Juliusruh und Glowe. Die bis zu 2 km breite Sandbank bietet endlose Weite, frische Ostseeluft und herrliche Ausblicke auf die Tromper Wiek.</p>
          <div class="card__highlights">
            <h4>Highlights</h4>
            <ul>
              <li>12 km Sandstrand – längster Rügens</li>
              <li>Naturlandschaft Tromper Wiek</li>
              <li>Blick auf Kap Arkona</li>
              <li>Ruhige, unberührte Natur</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card__img">
          <img src="/images/kreidefelsen.jpg" alt="Kreidefelsenpfad Stubbenkammer" loading="lazy" />
        </div>
        <div class="card__body">
          <div class="card__meta">
            <span class="badge badge--green">Moderat</span>
            <span class="badge badge--blue">8–12 km · 3–4 Std.</span>
          </div>
          <h3 class="card__title">Kreidefelsenpfad Stubbenkammer</h3>
          <p class="card__text">Dieser anspruchsvollere Küstenpfad zwischen Sassnitz und Lohme führt direkt an der Abbruchkante der Kreidefelsen entlang. Dramatische Ausblicke, das Rauschen der Ostsee tief unten und der Kontrast zwischen leuchtendem Weiß und tiefem Blau machen diesen Weg unvergesslich.</p>
          <div class="card__highlights">
            <h4>Highlights</h4>
            <ul>
              <li>Königsstuhl & Nationalpark-Zentrum</li>
              <li>Wissower Klinken</li>
              <li>Victoriasicht & Arndt-Sicht</li>
              <li>Dramatische Klippenabschnitte</li>
            </ul>
          </div>
        </div>
      </div>

    </div>

    <div class="tip-box">
      <p class="tip-box__title"><span class="material-symbols-rounded" style="color:var(--primary);font-size:1.1rem;">lightbulb</span> Tipp vom Gastgeber</p>
      <p>Unsere Unterkunft liegt nur wenige Kilometer vom Schmachter See entfernt. Fahrräder können in Binz ausgeliehen werden – kombinieren Sie Rad und Wandern für perfekte Tagestouren.</p>
    </div>
  </div>
</section>

<div class="cta-strip">
  <h2>Ihr Ausgangspunkt für Wanderabenteuer</h2>
  <p>Buchen Sie Ihre Unterkunft – zentral und naturnah.</p>
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