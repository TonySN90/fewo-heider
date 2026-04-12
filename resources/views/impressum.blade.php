@extends('layouts.pages')

@section('title', 'Impressum')

@section('nav')
  <a href="{{ url('/') }}">Startseite</a>
  <a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a>
  <a href="{{ url('/#kontakt') }}">Anfragen</a>
@endsection

@section('content')

<header class="page-header">
  <a href="{{ url('/') }}">← Zurück zur Website</a>
  <h1>Impressum</h1>
</header>

<div class="container legal-content">
  <h2>Angaben gemäß § 5 TMG</h2>
  <p>
    <strong>Max Mustermann</strong><br />
    Musterstraße 1<br />
    12345 Musterstadt<br />
    Deutschland
  </p>

  <h2>Kontakt</h2>
  <p>
    Telefon: <a href="tel:+4912345678">01234 56789</a><br />
    E-Mail: <a href="mailto:info@mustermann-fewo.de">info@mustermann-fewo.de</a>
  </p>

  <h2>Umsatzsteuer-ID</h2>
  <p>
    Als Kleinunternehmer im Sinne von § 19 UStG wird keine Umsatzsteuer berechnet und in Rechnung gestellt.
  </p>

  <h2>Haftung für Inhalte</h2>
  <p>
    Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht unter der Verpflichtung, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen.
  </p>
  <p>
    Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.
  </p>

  <h2>Haftung für Links</h2>
  <p>
    Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich.
  </p>

  <h2>Urheberrecht</h2>
  <p>
    Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers.
  </p>
</div>

@endsection

@section('footer')
  <p>© {{ date('Y') }} Musterferienwohnung &nbsp;|&nbsp; <a href="{{ url('/datenschutz') }}">Datenschutz</a></p>
@endsection
