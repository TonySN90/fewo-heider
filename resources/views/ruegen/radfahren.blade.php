@extends('layouts.pages')

@section('meta')
  <meta name="description" content="Radfahren auf Rügen – die schönsten Radrouten, vom Rügen-Rundweg bis zum Ostseeküstenradweg." />
@endsection

@section('title', 'Radfahren auf Rügen')

@section('nav')
  <a href="{{ url('/') }}">Startseite</a>
  <a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a>
  <a href="{{ url('/#kontakt') }}">Anfragen</a>
@endsection

@section('content')

<div class="page-hero">
  <img src="/images/radweg.jpg" alt="Radfahren auf Rügen" />
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content container">
    <div class="breadcrumb">
      <a href="{{ url('/') }}">Startseite</a> <span>/</span>
      <a href="{{ url('/ruegen-erleben') }}">Rügen erleben</a> <span>/</span>
      <span>Radfahren</span>
    </div>
    <h1>Radfahren auf <em>Rügen</em></h1>
  </div>
</div>

<section class="content">
  <div class="container">
    <div class="section-intro">
      <h2>Rügen per Rad entdecken</h2>
      <div class="divider"></div>
      <p>Flache Strecken, ausgebaute Radwege und abwechslungsreiche Landschaften machen Rügen zu einem der beliebtesten Radreiseziele in Deutschland. Von kurzen Ausflügen bis zur mehrtägigen Inselrunde.</p>
    </div>

    <div class="route-list">

      <div class="route">
        <div class="route__body">
          <p class="route__label">Mehrtages-Tour</p>
          <h2 class="route__title">Rügen-Rundweg</h2>
          <p class="route__text">Die Königsdisziplin unter Rügens Radtouren: Der Rundweg umrundet die gesamte Insel und führt durch alle typischen Landschaften – Kreideküste, Boddenlandschaften, Bäderarchitektur und stille Dörfer. Die Route ist in fünf komfortable Tagesetappen aufgeteilt und ideal für einen Wochenurlaub auf dem Fahrrad.</p>
          <span class="diff-badge diff--easy">Leicht – überwiegend flach</span>
        </div>
        <div class="route__stats">
          <div class="stat">
            <p class="stat__value">275 km</p>
            <p class="stat__label">Gesamtlänge</p>
          </div>
          <div class="stat">
            <p class="stat__value">5</p>
            <p class="stat__label">Etappen</p>
          </div>
        </div>
      </div>

      <div class="route">
        <div class="route__body">
          <p class="route__label">Fernradweg · D2</p>
          <h2 class="route__title">Ostseeküstenradweg</h2>
          <p class="route__text">Der Ostseeküstenradweg (EuroVelo 10) ist einer der bekanntesten Fernradwege Europas. Der Rügen-Abschnitt führt entlang traumhafter Küstenabschnitte, vorbei an Steilküsten und durch Hansestädte. Gut ausgeschildert, flach und für alle Fitnesslevel geeignet.</p>
          <span class="diff-badge diff--easy">Leicht</span>
        </div>
        <div class="route__stats">
          <div class="stat">
            <p class="stat__value">278 km</p>
            <p class="stat__label">Rügen-Abschnitt</p>
          </div>
        </div>
      </div>

      <div class="route">
        <div class="route__body">
          <p class="route__label">Tagesausflug ab Binz</p>
          <h2 class="route__title">Schmachter See Runde</h2>
          <p class="route__text">Ein perfekter Halbtagesausflug ab Binz: Durch den Wald am Schmachter See, vorbei am Jagdschloss Granitz und zurück über Felder und Wiesen. Die Strecke kombiniert Natur und Kultur auf einem kompakten, gut befahrbaren Kurs – ideal für Familien mit Kindern.</p>
          <span class="diff-badge diff--easy">Leicht</span>
        </div>
        <div class="route__stats">
          <div class="stat">
            <p class="stat__value">12 km</p>
            <p class="stat__label">Länge</p>
          </div>
          <div class="stat">
            <p class="stat__value">1,5 Std.</p>
            <p class="stat__label">Dauer</p>
          </div>
        </div>
      </div>

      <div class="route">
        <div class="route__body">
          <p class="route__label">Tagesausflug · Kultur</p>
          <h2 class="route__title">Radweg nach Putbus</h2>
          <p class="route__text">Von Gustow durch ruhige Felder und Wälder in die klassizistische „Weiße Stadt" Putbus – vorbei an gepflegten Dörfern und durch unberührte Landschaft. In Putbus wartet der einzigartige Circus-Platz und der Schlosspark auf eine Pause. Rückfahrt bequem mit dem Rasenden Roland.</p>
          <span class="diff-badge diff--medium">Moderat</span>
        </div>
        <div class="route__stats">
          <div class="stat">
            <p class="stat__value">19 km</p>
            <p class="stat__label">Länge</p>
          </div>
          <div class="stat">
            <p class="stat__value">1,5–2 Std.</p>
            <p class="stat__label">Dauer</p>
          </div>
        </div>
      </div>

    </div>

    <div class="tip-box">
      <p class="tip-box__title"><span class="material-symbols-rounded" style="color:var(--primary);font-size:1.1rem;">directions_bike</span> Fahrradverleih in der Nähe</p>
      <p>In Binz (ca. 3 km) und Sellin gibt es mehrere Fahrradverleihstationen – vom einfachen Stadtrad bis zum E-Bike. Unsere Ferienwohnung verfügt über einen abschließbaren Fahrradstellplatz direkt am Haus.</p>
    </div>
  </div>
</section>

<div class="cta-strip">
  <h2>Rügen auf zwei Rädern</h2>
  <p>Buchen Sie Ihre Unterkunft – ideal als Ausgangspunkt für alle Radtouren.</p>
  <a href="{{ url('/#kontakt') }}" class="btn btn--white"><span class="material-symbols-rounded">mail</span> Jetzt anfragen</a>
  <a href="{{ url('/ruegen-erleben') }}" class="btn btn--outline">Alle Kategorien</a>
</div>

@endsection

@section('footer')
  <p>© {{ date('Y') }} Musterferienwohnung</p>
  <nav class="footer__legal">
    <a href="{{ url('/impressum') }}">Impressum</a>
    <a href="{{ url('/datenschutz') }}">Datenschutz</a>
  </nav>
@endsection