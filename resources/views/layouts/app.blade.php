<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  @yield('meta')
  <title>@yield('title', 'Ferienwohnung Heider – Rügen')</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Open+Sans:wght@300;400;600&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <!-- FSLightbox (kein ES-Modul, direkter Script-Tag) -->
  <script src="/fslightbox.js" defer></script>

  @vite(['resources/js/main.ts'])

  @php $tenantTheme = current_tenant()?->theme; @endphp
  @if($tenantTheme)
  <style>
    :root {
      @if($tenantTheme->color_primary)--color-primary: {{ $tenantTheme->color_primary }};
      @endif
      @if($tenantTheme->color_primary_dark)--color-primary-dark: {{ $tenantTheme->color_primary_dark }};
      @endif
      @if($tenantTheme->color_secondary)--color-secondary: {{ $tenantTheme->color_secondary }};
      @endif
      @if($tenantTheme->color_bg)--color-bg: {{ $tenantTheme->color_bg }};
      @endif
      @if($tenantTheme->color_bg_alt)--color-bg-alt: {{ $tenantTheme->color_bg_alt }};
      @endif
      @if($tenantTheme->color_border)--color-border: {{ $tenantTheme->color_border }};
      @endif
      @if($tenantTheme->color_footer_top)--color-footer-top: {{ $tenantTheme->color_footer_top }};
      @endif
      @if($tenantTheme->color_footer_bot)--color-footer-bot: {{ $tenantTheme->color_footer_bot }};
      @endif
    }
    [data-theme="dark"] {
      @if($tenantTheme->dark_color_primary)--color-primary: {{ $tenantTheme->dark_color_primary }};
      @endif
      @if($tenantTheme->dark_color_primary_dark)--color-primary-dark: {{ $tenantTheme->dark_color_primary_dark }};
      @endif
      @if($tenantTheme->dark_color_secondary)--color-secondary: {{ $tenantTheme->dark_color_secondary }};
      @endif
      @if($tenantTheme->dark_color_bg)--color-bg: {{ $tenantTheme->dark_color_bg }};
      @endif
      @if($tenantTheme->dark_color_bg_alt)--color-bg-alt: {{ $tenantTheme->dark_color_bg_alt }};
      @endif
      @if($tenantTheme->dark_color_border)--color-border: {{ $tenantTheme->dark_color_border }};
      @endif
      @if($tenantTheme->dark_color_footer_top)--color-footer-top: {{ $tenantTheme->dark_color_footer_top }};
      @endif
      @if($tenantTheme->dark_color_footer_bot)--color-footer-bot: {{ $tenantTheme->dark_color_footer_bot }};
      @endif
    }
  </style>
  @endif
  {{-- Anti-FOUC: Dark-Mode vor dem ersten Paint setzen --}}
  <script>if(localStorage.getItem('theme')==='dark'){document.documentElement.setAttribute('data-theme','dark');}</script>
</head>
<body>
  @yield('content')
</body>
</html>
