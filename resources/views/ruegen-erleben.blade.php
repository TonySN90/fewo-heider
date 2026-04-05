@extends('layouts.pages')

@section('meta')
  <meta name="description" content="Rügen erleben – Wandern, Radfahren, Ausflugsziele, Sehenswürdigkeiten, Schlösser & Parks und Familienaktivitäten auf der schönsten Insel Deutschlands." />
@endsection

@section('title', 'Rügen erleben – Ferienwohnung Heider')

@section('nav')
  <a href="{{ url('/#ueber-uns') }}">Die Wohnung</a>
  <a href="{{ url('/#preise') }}">Preise</a>
  <a href="{{ url('/ruegen-erleben') }}" class="active">Rügen erleben</a>
  <a href="{{ url('/#kontakt') }}">Anfragen</a>
@endsection

@section('content')

<header class="page-header">
  <a href="{{ url('/') }}" class="page-header__back">
    <span class="material-symbols-rounded" style="font-size:1rem;">arrow_back</span> Zurück zur Startseite
  </a>
  <p class="page-header__eyebrow">Insel Rügen</p>
  <h1>Rügen <em>erleben</em></h1>
  <p>Entdecken Sie die schönsten Ecken der Insel – von Kreidefelsen und Buchenwäldern bis zu historischen Schlössern und familienfreundlichen Ausflügen.</p>
</header>

<section class="categories">
  <div class="container">
    <div class="categories__grid">

      <a href="{{ url('/ruegen/wandern') }}" class="category-card">
        <div class="category-card__img">
          <img src="/images/wandern2.jpg" alt="Wandern auf Rügen" loading="lazy" />
        </div>
        <div class="category-card__body">
          <span class="category-card__icon material-symbols-rounded">hiking</span>
          <h2 class="category-card__title">Wandern</h2>
          <p class="category-card__desc">Atemberaubende Wanderwege entlang der Kreideküste, durch UNESCO-Buchenwälder und am Hochufer des Nationalparks Jasmund.</p>
          <span class="category-card__link">Mehr entdecken <span class="material-symbols-rounded" style="font-size:1rem;">arrow_forward</span></span>
        </div>
      </a>

      <a href="{{ url('/ruegen/ausflugsziele') }}" class="category-card">
        <div class="category-card__img">
          <img src="/images/sellin.jpg" alt="Ausflugsziele Rügen" loading="lazy" />
        </div>
        <div class="category-card__body">
          <span class="category-card__icon material-symbols-rounded">explore</span>
          <h2 class="category-card__title">Ausflugsziele</h2>
          <p class="category-card__desc">Von der eleganten Seebrücke in Sellin bis zum wilden Kap Arkona – die schönsten Orte und Ausflugsziele der Insel Rügen.</p>
          <span class="category-card__link">Mehr entdecken <span class="material-symbols-rounded" style="font-size:1rem;">arrow_forward</span></span>
        </div>
      </a>

      <a href="{{ url('/ruegen/sehenswuerdigkeiten') }}" class="category-card">
        <div class="category-card__img">
          <img src="/images/kap_arkona.jpg" alt="Sehenswürdigkeiten Rügen" loading="lazy" />
        </div>
        <div class="category-card__body">
          <span class="category-card__icon material-symbols-rounded">museum</span>
          <h2 class="category-card__title">Sehenswürdigkeiten</h2>
          <p class="category-card__desc">Der Königsstuhl, die Störtebeker Festspiele, die Leuchttürme von Kap Arkona – Rügens historische und natürliche Highlights.</p>
          <span class="category-card__link">Mehr entdecken <span class="material-symbols-rounded" style="font-size:1rem;">arrow_forward</span></span>
        </div>
      </a>

      <a href="{{ url('/ruegen/radfahren') }}" class="category-card">
        <div class="category-card__img">
          <img src="/images/radweg.jpg" alt="Radfahren auf Rügen" loading="lazy" />
        </div>
        <div class="category-card__body">
          <span class="category-card__icon material-symbols-rounded">directions_bike</span>
          <h2 class="category-card__title">Radfahren</h2>
          <p class="category-card__desc">Rügen ist ein Paradies für Radfahrer – flache Strecken, gut ausgebaute Radwege und herrliche Küstenlandschaften auf 275 km Rundweg.</p>
          <span class="category-card__link">Mehr entdecken <span class="material-symbols-rounded" style="font-size:1rem;">arrow_forward</span></span>
        </div>
      </a>

      <a href="{{ url('/ruegen/schloesser-parks') }}" class="category-card">
        <div class="category-card__img">
          <img src="/images/park_natur.jpg" alt="Schlösser und Parks Rügen" loading="lazy" />
        </div>
        <div class="category-card__body">
          <span class="category-card__icon material-symbols-rounded">castle</span>
          <h2 class="category-card__title">Schlösser & Parks</h2>
          <p class="category-card__desc">Das Jagdschloss Granitz, die klassizistische „Weiße Stadt" Putbus und prächtige Parkanlagen – Rügens fürstliches Erbe.</p>
          <span class="category-card__link">Mehr entdecken <span class="material-symbols-rounded" style="font-size:1rem;">arrow_forward</span></span>
        </div>
      </a>

      <a href="{{ url('/ruegen/familie') }}" class="category-card">
        <div class="category-card__img">
          <img src="/images/rasender_roland.jpg" alt="Rügen für die ganze Familie" loading="lazy" />
        </div>
        <div class="category-card__body">
          <span class="category-card__icon material-symbols-rounded">family_restroom</span>
          <h2 class="category-card__title">Rügen für die ganze Familie</h2>
          <p class="category-card__desc">Dinosaurierland, Rasender Roland, Karls Erlebnis-Dorf und kilometerlange Sandstrände – Rügen begeistert Klein und Groß.</p>
          <span class="category-card__link">Mehr entdecken <span class="material-symbols-rounded" style="font-size:1rem;">arrow_forward</span></span>
        </div>
      </a>

    </div>
  </div>
</section>

<div class="teaser">
  <h2>Ihr Ausgangspunkt für all das</h2>
  <p>Unsere Ferienwohnung in Zirkow/Serams liegt zentral – ideal für Tagesausflüge in alle Richtungen der Insel.</p>
  <a href="{{ url('/#kontakt') }}" class="btn btn--white">
    <span class="material-symbols-rounded">mail</span> Jetzt anfragen
  </a>
</div>

@endsection

@section('footer')
  <p>© 2026 Ferienwohnung Heider · Serams 8A · 18528 Zirkow/Serams</p>
  <nav class="footer__legal">
    <a href="{{ url('/impressum') }}">Impressum</a>
    <a href="{{ url('/datenschutz') }}">Datenschutz</a>
  </nav>
@endsection
