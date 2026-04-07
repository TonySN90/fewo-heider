{{-- Layout: feature (sehenswuerdigkeiten, familie) --}}
<section class="content">
  <div class="container">

    @php $introHeading = $entries->first()?->blocks->firstWhere('type', 'heading'); @endphp
    @if ($introHeading)
      <div class="section-intro">
        <h2>{{ $introHeading->content }}</h2>
        <div class="divider"></div>
      </div>
    @endif

    <div class="features">
      @foreach ($entries as $i => $entry)
        @php
          $isRev      = $i % 2 !== 0;
          $blocks     = $entry->blocks;
          $headings   = $blocks->where('type', 'heading')->values();
          $textBlocks = $blocks->where('type', 'text')->values();

          // Kategorie-Label = zweiter Heading-Block (erster ist oft der Seiten-Intro)
          $category = $headings->skip(1)->first()?->content ?? $headings->first()?->content;
          // Ersten und zweiten Text als Beschreibung
          $desc1    = $textBlocks->first()?->content;
          $desc2    = $textBlocks->skip(1)->first()?->content;
          // Letzter Block oft Info-Zeilen (Öffnungszeiten etc.)
          $infoLine = $textBlocks->last()?->content;
          $isInfoLine = $infoLine && ($infoLine !== $desc1) && ($infoLine !== $desc2);
        @endphp
        <div class="feature {{ $isRev ? 'feature--rev' : '' }}">
          @if ($entry->cover_image)
            <div class="feature__img">
              <img src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" loading="lazy" />
            </div>
          @endif
          <div class="feature__body">
            @if ($category && $category !== ($introHeading?->content))
              <p class="feature__category">{{ $category }}</p>
            @endif
            <h2 class="feature__title">{{ $entry->title }}</h2>
            @if ($desc1)
              <p class="feature__text">{{ $desc1 }}</p>
            @endif
            @if ($desc2 && $desc2 !== $desc1 && ! $isInfoLine)
              <p class="feature__text" style="margin-top:.75rem">{{ $desc2 }}</p>
            @endif
            @if ($isInfoLine)
              <div class="feature__info">
                @foreach (array_filter(array_map('trim', explode('·', $infoLine))) as $item)
                  <span class="info-item">
                    <span class="material-symbols-rounded">info</span>
                    {{ $item }}
                  </span>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>

  </div>
</section>
