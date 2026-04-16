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

@if ($entry->cover_image)
  <div class="page-hero" style="background-image:url('{{ Storage::url($entry->cover_image) }}')">
    <div class="page-hero__overlay"></div>
    <div class="page-hero__content container">
      <div class="breadcrumb">
        <a href="{{ url('/') }}">Startseite</a> <span>/</span>
        <a href="{{ route('pages.group', $group->slug) }}">{{ $group->nav_label }}</a> <span>/</span>
        <a href="{{ route('pages.show', [$group->slug, $page->slug]) }}">{{ $page->title }}</a> <span>/</span>
        <span>{{ $entry->title }}</span>
      </div>
      <h1>{{ $entry->title }}</h1>
    </div>
  </div>
@endif

<div class="container" style="padding:3rem 1rem;max-width:800px;margin:0 auto">

  @if (! $entry->cover_image)
    <div class="breadcrumb" style="margin-bottom:2rem">
      <a href="{{ url('/') }}">Startseite</a> <span>/</span>
      <a href="{{ route('pages.group', $group->slug) }}">{{ $group->nav_label }}</a> <span>/</span>
      <a href="{{ route('pages.show', [$group->slug, $page->slug]) }}">{{ $page->title }}</a> <span>/</span>
      <span>{{ $entry->title }}</span>
    </div>
    <h1 style="margin-bottom:2rem">{{ $entry->title }}</h1>
  @endif

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
    <a href="{{ route('pages.show', [$group->slug, $page->slug]) }}" style="color:var(--color-primary)">
      ← Zurück zu {{ $page->title }}
    </a>
  </div>

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
