@extends('layouts.pages')

@section('title', $entry->title . ' – ' . $page->title . ' – Ferienwohnung Heider')

@section('nav')
  <a href="{{ url('/') }}">Startseite</a>
  <a href="{{ route('ruegen.index') }}">Rügen erleben</a>
  <a href="{{ url('/#kontakt') }}">Anfragen</a>
@endsection

@section('content')

<div class="container" style="padding:3rem 1rem;max-width:800px;margin:0 auto">

  <div class="breadcrumb" style="margin-bottom:2rem">
    <a href="{{ url('/') }}">Startseite</a> <span>/</span>
    <a href="{{ route('ruegen.index') }}">Rügen erleben</a> <span>/</span>
    <a href="{{ route('ruegen.page', $page->slug) }}">{{ $page->title }}</a> <span>/</span>
    <span>{{ $entry->title }}</span>
  </div>

  <h1 style="margin-bottom:2rem">{{ $entry->title }}</h1>

  @foreach ($entry->blocks as $block)
    @if ($block->type === 'heading')
      <h2 style="margin:2rem 0 .75rem">{{ $block->content }}</h2>
    @elseif ($block->type === 'image')
      <figure style="margin:1.5rem 0">
        <img src="{{ $block->content }}" alt="" style="width:100%;border-radius:8px" loading="lazy" />
      </figure>
    @else
      <p style="margin-bottom:1rem;line-height:1.7">{{ $block->content }}</p>
    @endif
  @endforeach

  <div style="margin-top:3rem">
    <a href="{{ route('ruegen.page', $page->slug) }}" style="color:var(--color-primary)">
      ← Zurück zu {{ $page->title }}
    </a>
  </div>

</div>

@endsection

@section('footer')
  <p>© {{ date('Y') }} Ferienwohnung Heider</p>
@endsection
