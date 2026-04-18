{{-- Layout: route (radfahren) --}}
<section class="content">
  <div class="container">

    @if (!empty($introHeading))
      <div class="section-intro">
        <h2>{{ $introHeading }}</h2>
        <div class="divider"></div>
        @if (!empty($introText))
          <p>{{ $introText }}</p>
        @endif
      </div>
    @endif

    @php $isEn = app()->getLocale() === 'en'; @endphp
    <div class="route-list">
      @foreach ($entries as $entry)
        @php
          $blocks     = $entry->blocks;
          $textEnBlock = $blocks->firstWhere('type', 'text_en');
          $textBlocks = $blocks->where('type', 'text')->values();
          $desc       = ($isEn && $textEnBlock) ? $textEnBlock->content : $textBlocks->first()?->content;
          $stats      = $textBlocks->last()?->content; // "Gesamtlänge: X km · Y Etappen · Schwierigkeit: ..."

          // Stats und Label aus dem letzten Textblock extrahieren
          $statItems = [];
          $diffLabel = null;
          $routeLabel = null;
          if ($stats) {
              $parts = array_map('trim', explode('·', $stats));
              foreach ($parts as $part) {
                  if (str_contains(strtolower($part), 'schwierigkeit')) {
                      preg_match('/Schwierigkeit:\s*(.+)/i', $part, $m);
                      $diffLabel = $m[1] ?? null;
                  } elseif (str_contains($part, ':')) {
                      [$label, $value] = array_map('trim', explode(':', $part, 2));
                      $statItems[] = ['label' => $label, 'value' => $value];
                  }
              }
          }
          // Route-Label aus erstem Heading-Block nach dem ersten Eintrag
          $labelBlock = $blocks->where('type', 'heading')->first();
          $routeLabel = $labelBlock?->content;
        @endphp
        <div class="route">
          <div class="route__body">
            @if ($routeLabel && $routeLabel !== $entry->title)
              <p class="route__label">{{ $routeLabel }}</p>
            @endif
            <h2 class="route__title">{{ $entry->localizedTitle() }}</h2>
            @if ($desc)
              <p class="route__text">{{ $desc }}</p>
            @endif
            @if ($diffLabel)
              @php
                $diffClass = str_contains(strtolower($diffLabel), 'moderat') ? 'diff--medium' : 'diff--easy';
              @endphp
              <span class="diff-badge {{ $diffClass }}">{{ $diffLabel }}</span>
            @endif
          </div>
          @if (count($statItems) > 0)
            <div class="route__stats">
              @foreach ($statItems as $stat)
                <div class="stat">
                  <p class="stat__value">{{ $stat['value'] }}</p>
                  <p class="stat__label">{{ $stat['label'] }}</p>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      @endforeach
    </div>

    @php $tip = $entries->first()?->blocks->where('type', 'text')->last(); @endphp
    @if (isset($tipText))
      <div class="tip-box">
        <p class="tip-box__title">
          <span class="material-symbols-rounded" style="color:var(--color-primary);font-size:1.1rem">directions_bike</span>
          Fahrradverleih in der Nähe
        </p>
        <p>{{ $tipText }}</p>
      </div>
    @endif

  </div>
</section>
