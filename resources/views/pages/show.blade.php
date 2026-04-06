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

<div class="container" style="padding:3rem 1rem">
  @if ($page->entries->isEmpty())
    <p style="color:#888;text-align:center">Noch keine Einträge vorhanden.</p>
  @else
    <div class="entries-grid">
      @foreach ($page->entries as $entry)
        <a href="{{ route('pages.entry', [$group->slug, $page->slug, $entry->slug]) }}" class="entry-card">
          @if ($entry->cover_image)
            <div class="entry-card__image">
              <img src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" loading="lazy" />
            </div>
          @endif
          <h2 class="entry-card__title">{{ $entry->title }}</h2>
          <span class="entry-card__link">Mehr lesen <span class="material-symbols-rounded" style="font-size:1rem">arrow_forward</span></span>
        </a>
      @endforeach
    </div>
  @endif
</div>

@endsection

@section('footer')
  <p>© {{ date('Y') }} Ferienwohnung Heider</p>
@endsection
