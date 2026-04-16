{{-- Layout: place-list (ausflugsziele) --}}
<section class="content">
  <div class="container">

    @php $intro = $entries->first()?->blocks->firstWhere('type', 'heading'); @endphp
    @if ($intro)
      <div class="section-intro">
        <h2>{{ $intro->content }}</h2>
        <div class="divider"></div>
      </div>
    @endif

    <div class="place-list">
      @foreach ($entries as $entry)
        @php
          $blocks     = $entry->blocks;
          $textBlocks = $blocks->where('type', 'text')->values();
          $desc       = $textBlocks->first()?->content;
          $distLine   = $textBlocks->last()?->content; // "Entfernung: ca. X km"
          // Tags aus dem letzten Block parsen wenn er Tags enthält
          $tags = [];
          if ($distLine && str_contains($distLine, 'Entfernung')) {
              // Distanz aus "Entfernung: ca. X km" extrahieren
              preg_match('/Entfernung:\s*(.+)/i', $distLine, $m);
              $dist = $m[1] ?? null;
          } else {
              $dist = null;
          }
        @endphp
        <div class="place {{ $entry->image_position === 'right' ? 'place--img-right' : '' }}">
          @if ($entry->cover_image)
            <div class="place__img">
              <img src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" loading="lazy" />
            </div>
          @endif
          <div class="place__body">
            @if ($dist)
              <p class="place__dist">
                <span class="material-symbols-rounded" style="font-size:0.9rem">location_on</span>
                {{ $dist }}
              </p>
            @endif
            <h2 class="place__title">{{ $entry->title }}</h2>
            @if ($desc)
              <p class="place__text">{{ $desc }}</p>
            @endif
          </div>
        </div>
      @endforeach
    </div>

  </div>
</section>
