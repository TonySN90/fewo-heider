@extends('layouts.pages')

@section('meta')
  <meta name="description" content="Rügen erleben – Wandern, Radfahren, Ausflugsziele, Sehenswürdigkeiten, Schlösser & Parks und Familienaktivitäten auf der schönsten Insel Deutschlands." />
@endsection

@section('title', 'Rügen erleben')

@push('header-nav-links')
  <li><a href="{{ url('/') }}">Startseite</a></li>
  <li><a href="{{ url('/#preise') }}">Preise</a></li>
  <li><a href="{{ url('/ruegen-erleben') }}" class="active">Rügen erleben</a></li>
  <li><a href="{{ url('/#kontakt') }}" class="nav__cta">{{ ui_labels()['nav_contact'] }}</a></li>
@endpush

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
      @forelse ($pages as $page)
        <a href="{{ route('ruegen.page', $page->slug) }}" class="category-card">
          <div class="category-card__body" style="padding-top:1.5rem">
            <h2 class="category-card__title">{{ $page->title }}</h2>
            @if ($page->description)
              <p class="category-card__desc">{{ $page->description }}</p>
            @endif
            <span class="category-card__link">Mehr entdecken <span class="material-symbols-rounded" style="font-size:1rem;">arrow_forward</span></span>
          </div>
        </a>
      @empty
        <p style="color:#aaa">Noch keine Kategorien vorhanden.</p>
      @endforelse
    </div>
  </div>
</section>

<div class="teaser">
  <h2>Ihr Ausgangspunkt für all das</h2>
  <p>Unsere Unterkunft liegt zentral auf der Insel – ideal für Tagesausflüge in alle Richtungen.</p>
  <a href="{{ url('/#kontakt') }}" class="btn btn--white">
    <span class="material-symbols-rounded">mail</span> Jetzt anfragen
  </a>
</div>

@endsection

@section('footer')
  <p>© {{ date('Y') }} Musterferienwohnung</p>
  <nav class="footer__legal">
    <a href="{{ url('/impressum') }}">Impressum</a>
    <a href="{{ url('/datenschutz') }}">Datenschutz</a>
  </nav>
@endsection
