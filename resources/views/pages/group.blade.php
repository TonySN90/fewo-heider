@extends('layouts.pages')

@section('meta')
  @if ($group->description)
    <meta name="description" content="{{ $group->description }}" />
  @endif
@endsection

@section('title', $group->nav_label)

@section('nav')
  <a href="{{ url('/') }}">Die Wohnung</a>
  <a href="{{ url('/#preise') }}">Preise</a>
  <a href="{{ url('/' . $group->slug) }}" class="active">{{ $group->nav_label }}</a>
  <a href="{{ url('/#kontakt') }}">Anfragen</a>
@endsection

@section('content')

<header class="page-header">
  <a href="{{ url('/') }}" class="page-header__back">
    <span class="material-symbols-rounded" style="font-size:1rem;">arrow_back</span> Zurück zur Startseite
  </a>
  <h1>{{ $group->title }}</h1>
  @if ($group->description)
    <p>{{ $group->description }}</p>
  @endif
</header>

<section class="categories">
  <div class="container">
    <div class="categories__grid">
      @forelse ($pages as $page)
        <a href="{{ route('pages.show', [$group->slug, $page->slug]) }}" class="category-card">
          @if ($page->cover_image)
            <div class="category-card__img">
              <img src="{{ Storage::url($page->cover_image) }}" alt="{{ $page->title }}" loading="lazy" />
            </div>
          @endif
          <div class="category-card__body">
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
  <p>Unsere Unterkunft liegt zentral – ideal als Ausgangspunkt für Ausflüge in alle Richtungen.</p>
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
