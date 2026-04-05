<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  @yield('meta')
  <title>@yield('title', 'Ferienwohnung Heider – Rügen')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Open+Sans:wght@300;400;600&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0&display=swap" rel="stylesheet" />
  @vite(['resources/css/pages.scss'])
</head>
<body>

<nav class="site-nav">
  <div class="site-nav__inner">
    <a href="{{ url('/') }}" class="site-nav__logo">Ferienwohnung<span>Heider</span></a>
    <button class="site-nav__burger" id="site-burger" aria-label="Menü öffnen" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
  <div class="site-nav__links">
    @yield('nav')
  </div>
</nav>

@yield('content')

<footer>
  @yield('footer')
</footer>

<script>
  const burger = document.getElementById('site-burger');
  const nav = document.querySelector('.site-nav__links');
  burger?.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('open');
    burger.classList.toggle('open', isOpen);
    burger.setAttribute('aria-expanded', String(isOpen));
    document.body.style.overflow = isOpen ? 'hidden' : '';
  });
  document.addEventListener('click', (e) => {
    if (nav.classList.contains('open') && !nav.contains(e.target) && !burger.contains(e.target)) {
      nav.classList.remove('open');
      burger.classList.remove('open');
      burger.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    }
  });
</script>

</body>
</html>
