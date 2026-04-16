<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  {!! seo() !!}
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Open+Sans:wght@300;400;600&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />

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
  {{-- Anti-FOUC: Barrierefreiheits-Einstellungen vor dem ersten Paint setzen --}}
  <script>(function(){var h=document.documentElement;var s=parseInt(localStorage.getItem('a11y-font-size-step')||'0',10);var z=[12,14,16,18,20];if(s>=-2&&s<=2){h.style.fontSize=z[s+2]+'px';}if(localStorage.getItem('a11y-high-contrast')==='1'){h.toggleAttribute('data-a11y-contrast',true);var cp={'--color-bg':'#ffffff','--color-bg-alt':'#f2f2f2','--color-surface':'#ffffff','--color-text':'#000000','--color-text-light':'#000000','--color-dark':'#000000','--color-border':'#000000','--color-primary':'#004d42','--color-primary-dark':'#003830','--color-secondary':'#004d42','--color-footer-top':'#000000','--color-footer-bot':'#000000'};Object.keys(cp).forEach(function(k){h.style.setProperty(k,cp[k]);});}if(localStorage.getItem('a11y-line-spacing')==='1'){h.toggleAttribute('data-a11y-spacing',true);}if(localStorage.getItem('a11y-highlight-links')==='1'){h.toggleAttribute('data-a11y-links',true);}if(localStorage.getItem('a11y-reduce-motion')==='1'){h.toggleAttribute('data-a11y-motion',true);}}());</script>

  @yield('meta')
</head>
<body>

@include('partials.header')

<main id="main-content">
  @yield('content')
</main>

<footer class="footer__bottom">
  @yield('footer')
</footer>

</body>
</html>
