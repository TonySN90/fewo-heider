{{-- Layout: hero-feature (schloesser-parks) --}}
<section class="content">
  <div class="container">

    @php $introHeading = $entries->first()?->blocks->firstWhere('type', 'heading'); @endphp
    @if ($introHeading)
      <div class="section-intro">
        <h2>{{ $introHeading->content }}</h2>
        <div class="divider"></div>
        @php $introText = $entries->first()?->blocks->firstWhere('type', 'text'); @endphp
        @if ($introText)
          <p>{{ $introText->content }}</p>
        @endif
      </div>
    @endif

    @php $hero = $entries->first(); @endphp
    @if ($hero)
      @php
        $heroBlocks = $hero->blocks;
        $heroTexts  = $heroBlocks->where('type', 'text')->values();
        $heroDesc1  = $heroTexts->first()?->content;
        $heroDesc2  = $heroTexts->skip(1)->first()?->content;
        // Facts aus letztem Block (Entfernung: X · Turmhöhe: Y etc.)
        $factsLine  = $heroTexts->last()?->content;
        $facts = [];
        if ($factsLine && $factsLine !== $heroDesc1) {
            foreach (array_filter(array_map('trim', explode('·', $factsLine))) as $part) {
                if (str_contains($part, ':')) {
                    [$label, $value] = array_map('trim', explode(':', $part, 2));
                    $facts[] = ['label' => $label, 'value' => $value];
                }
            }
        }
      @endphp
      <div class="hero-feature">
        @if ($hero->cover_image)
          <div class="hero-feature__img">
            <img src="{{ Storage::url($hero->cover_image) }}" alt="{{ $hero->title }}" loading="lazy" />
          </div>
        @endif
        <div class="hero-feature__body">
          <h2 class="hero-feature__title">{{ $hero->title }}</h2>
          @if ($heroDesc1)
            <p class="hero-feature__text">{{ $heroDesc1 }}</p>
          @endif
          @if ($heroDesc2 && $heroDesc2 !== $factsLine)
            <p class="hero-feature__text" style="margin-top:.75rem">{{ $heroDesc2 }}</p>
          @endif
          @if (count($facts) > 0)
            <div class="hero-feature__facts">
              @foreach ($facts as $fact)
                <div class="fact">
                  <p class="fact__label">{{ $fact['label'] }}</p>
                  <p class="fact__value">{{ $fact['value'] }}</p>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    @endif

    {{-- Restliche Einträge als 3-Spalten-Grid --}}
    @if ($entries->count() > 1)
      <div class="cards cards--three">
        @foreach ($entries->skip(1) as $entry)
          @php
            $blocks   = $entry->blocks;
            $texts    = $blocks->where('type', 'text')->values();
            $desc     = $texts->first()?->content;
            $yearLine = $texts->last()?->content;
            $year     = ($yearLine && str_contains($yearLine, ':')) ? null : $yearLine;
            // Jahr aus Heading holen
            $headings = $blocks->where('type', 'heading')->values();
          @endphp
          <div class="card">
            @if ($entry->cover_image)
              <div class="card__img card__img--ratio-4-3">
                <img src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" loading="lazy" />
              </div>
            @endif
            <div class="card__body">
              @if ($year)
                <p class="card__year">{{ $year }}</p>
              @endif
              <h3 class="card__title">{{ $entry->title }}</h3>
              @if ($desc)
                <p class="card__text">{{ $desc }}</p>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>
</section>
