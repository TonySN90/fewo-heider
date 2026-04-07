@extends('layouts.pages')

@section('title', $page->title . ' – ' . $group->nav_label . ' – Ferienwohnung Heider')

@section('nav')
  <a href="{{ url('/') }}">Startseite</a>
  <a href="{{ route('pages.group', $group->slug) }}">{{ $group->nav_label }}</a>
  <a href="{{ url('/#kontakt') }}">Anfragen</a>
@endsection

@section('content')

<div class="page-hero" @if ($page->cover_image) style="background-image:url('{{ Storage::url($page->cover_image) }}')" @endif>
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content container">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">Startseite</a> <span>/</span>
      <a href="{{ route('pages.group', $group->slug) }}">{{ $group->nav_label }}</a> <span>/</span>
      <span>{{ $page->title }}</span>
    </div>
    <h1>{{ $page->title }}</h1>
    @if ($page->description)
      <p>{{ $page->description }}</p>
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
  <h2>{{ $page->title }} auf Rügen erleben</h2>
  <p>Buchen Sie Ihre Ferienwohnung in Zirkow/Serams – ideal als Ausgangspunkt.</p>
  <a href="{{ url('/#kontakt') }}" class="btn btn--white">
    <span class="material-symbols-rounded">mail</span> Jetzt anfragen
  </a>
  <a href="{{ route('pages.group', $group->slug) }}" class="btn btn--outline">Alle Kategorien</a>
</div>

@endsection

@section('footer')
  <p>© {{ date('Y') }} Ferienwohnung Heider · Serams 8A · 18528 Zirkow/Serams</p>
  <nav class="footer__legal">
    <a href="{{ url('/impressum') }}">Impressum</a>
    <a href="{{ url('/datenschutz') }}">Datenschutz</a>
  </nav>
@endsection
