@extends('layouts.pages')

@push('header-nav-links')
  <li><a href="{{ url('/') }}">Startseite</a></li>
  <li><a href="{{ route('pages.group', $group->slug) }}">{{ $group->nav_label }}</a></li>
  <li><a href="{{ url('/#kontakt') }}" class="nav__cta">{{ ui_labels()['nav_contact'] }}</a></li>
@endpush

@section('content')

<div class="page-hero" @if ($page->cover_image) style="background-image:url('{{ Storage::url($page->cover_image) }}')" @endif>
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content container">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">Startseite</a> <span>/</span>
      <a href="{{ route('pages.group', $group->slug) }}">{{ $group->nav_label }}</a> <span>/</span>
      <span>{{ $page->localizedTitle() }}</span>
    </div>
    <h1>{{ $page->localizedTitle() }}</h1>
    @if ($page->localizedDescription())
      <p>{{ $page->localizedDescription() }}</p>
    @endif
  </div>
</div>

@if ($page->entries->isEmpty())
  <div class="container" style="padding:3rem 1rem;text-align:center;color:#888">
    Noch keine Einträge vorhanden.
  </div>
@else
  @switch($page->layout)
    @case('place-list')
      @include('pages.layouts.place-list', ['entries' => $page->entries])
      @break
    @case('route')
      @include('pages.layouts.route', ['entries' => $page->entries])
      @break
    @case('feature')
      @include('pages.layouts.feature', ['entries' => $page->entries])
      @break
    @case('hero-feature')
      @include('pages.layouts.hero-feature', ['entries' => $page->entries])
      @break
    @default
      @include('pages.layouts.cards', ['entries' => $page->entries])
  @endswitch
@endif

<div class="cta-strip">
  <h2>{{ $page->localizedTitle() }} auf Rügen erleben</h2>
  <p>Buchen Sie Ihre Unterkunft – ideal als Ausgangspunkt.</p>
  <a href="{{ url('/#kontakt') }}" class="btn btn--white">
    <span class="material-symbols-rounded">mail</span> Jetzt anfragen
  </a>
  <a href="{{ route('pages.group', $group->slug) }}" class="btn btn--outline">Alle Kategorien</a>
</div>

@endsection

@section('footer')
  <p>© {{ date('Y') }} Musterferienwohnung</p>
  <nav class="footer__legal">
    <a href="{{ url('/impressum') }}">Impressum</a>
    <a href="{{ url('/datenschutz') }}">Datenschutz</a>
  </nav>
@endsection
