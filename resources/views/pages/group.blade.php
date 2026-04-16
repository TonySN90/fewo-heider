@extends('layouts.pages')

@push('header-nav-links')
  @php
    $ui = ui_labels();
    $navLabels = [
      'about'     => ['href' => url('/#about'),     'label' => $ui['nav_about']],
      'amenities' => ['href' => url('/#amenities'), 'label' => $ui['nav_amenities']],
      'gallery'   => ['href' => url('/#gallery'),   'label' => $ui['nav_gallery']],
      'pricing'   => ['href' => url('/#pricing'),   'label' => $ui['nav_pricing']],
      'arrival'   => ['href' => url('/#arrival'),   'label' => $ui['nav_arrival']],
    ];
  @endphp
  @foreach ($orderedSections as $sec)
    @if (isset($navLabels[$sec->section_key]))
      <li><a href="{{ $navLabels[$sec->section_key]['href'] }}">{{ $navLabels[$sec->section_key]['label'] }}</a></li>
    @endif
  @endforeach
  @foreach ($pageGroups as $pg)
    <li><a href="{{ url('/' . $pg->slug) }}" {{ $pg->slug === $group->slug ? 'class=active' : '' }}>{{ $pg->nav_label }}</a></li>
  @endforeach
  @if (in_array('contact', $visibleSections))
    <li><a href="{{ url('/#kontakt') }}" class="nav__cta">{{ $ui['nav_contact'] }}</a></li>
  @endif
@endpush

@section('content')

<header class="page-header">
  <a href="{{ url('/') }}" class="page-header__back">
    <span class="material-symbols-rounded" style="font-size:1rem;">arrow_back</span> Zurück zur Startseite
  </a>
  <h1>{{ $group->localizedTitle() }}</h1>
  @if ($group->localizedDescription())
    <p>{{ $group->localizedDescription() }}</p>
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
            <h2 class="category-card__title">{{ $page->localizedTitle() }}</h2>
            @if ($page->localizedDescription())
              <p class="category-card__desc">{{ $page->localizedDescription() }}</p>
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
  <div class="footer__bottom-inner">
    <p>© {{ date('Y') }} Musterferienwohnung</p>
    <nav class="footer__legal">
      <a href="{{ url('/impressum') }}">Impressum</a>
      <a href="{{ url('/datenschutz') }}">Datenschutz</a>
    </nav>
  </div>
@endsection
