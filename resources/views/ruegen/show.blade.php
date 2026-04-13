@extends('layouts.pages')

@section('title', $page->title . ' auf Rügen')

@push('header-nav-links')
  <li><a href="{{ url('/') }}">Startseite</a></li>
  <li><a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a></li>
  <li><a href="{{ url('/#kontakt') }}" class="nav__cta">{{ ui_labels()['nav_contact'] }}</a></li>
@endpush

@section('content')

<div class="page-hero">
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content container">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">Startseite</a> <span>/</span>
      <a href="{{ route('ruegen.index') }}">Rügen erleben</a> <span>/</span>
      <span>{{ $page->title }}</span>
    </div>
    <h1>{{ $page->title }}</h1>
    @if ($page->description)
      <p>{{ $page->description }}</p>
    @endif
  </div>
</div>

<div class="container" style="padding:3rem 1rem">
  @if ($page->entries->isEmpty())
    <p style="color:#888;text-align:center">Noch keine Einträge vorhanden.</p>
  @else
    <div class="entries-grid">
      @foreach ($page->entries as $entry)
        <a href="{{ route('ruegen.entry', [$page->slug, $entry->slug]) }}" class="entry-card">
          <h2 class="entry-card__title">{{ $entry->title }}</h2>
          <span class="entry-card__link">Mehr lesen <span class="material-symbols-rounded" style="font-size:1rem">arrow_forward</span></span>
        </a>
      @endforeach
    </div>
  @endif
</div>

@endsection

@section('footer')
  <p>© {{ date('Y') }} Musterferienwohnung</p>
@endsection
