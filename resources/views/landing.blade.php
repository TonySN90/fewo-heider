@extends('layouts.landing')

@section('title', 'CO-DING Webtemplates – Erstellen Sie Ihre Website')

@section('content')

    <header class="lp-header">
        <span class="lp-logo">CO-DING Webtemplates</span>
        <a href="{{ route('admin.login') }}" class="lp-header-link">Anmelden</a>
    </header>

    <main class="lp-hero">
        <span class="lp-eyebrow">Website-Plattform für Ihre Branche</span>
        <h1 class="lp-headline">Erstellen Sie hier einfach Ihre Website mit wenigen Schritten</h1>
        <p class="lp-subline">
            Präsentieren Sie Ihr Angebot professionell im Internet – ohne technische Vorkenntnisse.
            Texte, Fotos, Galerie und mehr in wenigen Minuten eingerichtet.
        </p>
        <a href="{{ route('admin.login') }}" class="lp-cta">Jetzt loslegen</a>
    </main>

    <section class="lp-features">
        <div class="lp-feature">
            <span class="material-symbols-rounded lp-feature-icon">web</span>
            <div class="lp-feature-title">Professionelle Präsentation</div>
            <p class="lp-feature-text">Gestalten Sie Ihre Website mit Texten, Fotos und einer Galerie – alles einfach über den Admin-Bereich.</p>
        </div>
        <div class="lp-feature">
            <span class="material-symbols-rounded lp-feature-icon">calendar_month</span>
            <div class="lp-feature-title">Kalender &amp; Preise</div>
            <p class="lp-feature-text">Pflegen Sie Verfügbarkeiten und Preise direkt in der Plattform. Ihre Besucher sehen immer aktuelle Informationen.</p>
        </div>
        <div class="lp-feature">
            <span class="material-symbols-rounded lp-feature-icon">palette</span>
            <div class="lp-feature-title">Individuelles Design</div>
            <p class="lp-feature-text">Passen Sie Farben, Inhalte und Struktur ganz nach Ihren Wünschen an – in wenigen Schritten zur fertigen Website.</p>
        </div>
    </section>

    <footer class="lp-footer">
        &copy; {{ date('Y') }} CO-DING Webtemplates &mdash; Alle Rechte vorbehalten
    </footer>

@endsection
