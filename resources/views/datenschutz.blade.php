@extends('layouts.pages')

@section('title', 'Datenschutzerklärung')

@push('header-nav-links')
  <li><a href="{{ url('/') }}">Startseite</a></li>
  <li><a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a></li>
  <li><a href="{{ url('/#kontakt') }}" class="nav__cta">{{ ui_labels()['nav_contact'] }}</a></li>
@endpush

@section('content')

<header class="page-header">
  <a href="{{ url('/') }}">← Zurück zur Website</a>
  <h1>Datenschutzerklärung</h1>
</header>

<div class="container legal-content">
  @if($content)
    {!! $content !!}
  @else
    <p style="color:#aaa;font-style:italic;">
      Keine Datenschutzerklärung hinterlegt.
    </p>
  @endif
</div>

@endsection

@section('footer')
  <p>© {{ date('Y') }} Musterferienwohnung &nbsp;|&nbsp; <a href="{{ url('/impressum') }}">Impressum</a></p>
@endsection
