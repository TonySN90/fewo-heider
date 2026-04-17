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
          $isRev      = ($entry->image_position ?? 'left') === 'right';
          $blocks     = $entry->blocks->sortBy('sort_order');
          $headings   = $blocks->where('type', 'heading')->values();
          $textBlocks = $blocks->where('type', 'text')->values();
          $infoBlocks = $blocks->where('type', 'info')->values();

          $category = $headings->skip(1)->first()?->content ?? $headings->first()?->content;
          $desc1 = $textBlocks->first()?->content;
          $desc2 = $textBlocks->skip(1)->first()?->content;

          // Info-Items vor oder nach Text? Vergleich der sort_order
          $infoFirst = $infoBlocks->isNotEmpty() && $textBlocks->isNotEmpty()
              && $infoBlocks->min('sort_order') < $textBlocks->min('sort_order');
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
            @if ($infoFirst && $infoBlocks->isNotEmpty())
              <div class="feature__info feature__info--top">
                @foreach ($infoBlocks as $item)
                  <span class="info-item">
                    <span class="material-symbols-rounded">{{ $item->icon ?? 'info' }}</span>
                    {{ $item->content }}
                  </span>
                @endforeach
              </div>
            @endif
            <h2 class="feature__title">{{ $entry->title }}</h2>
            @if ($desc1)
              <p class="feature__text">{{ $desc1 }}</p>
            @endif
            @if ($desc2 && $desc2 !== $desc1)
              <p class="feature__text">{{ $desc2 }}</p>
            @endif
            @if (!$infoFirst && $infoBlocks->isNotEmpty())
              <div class="feature__info">
                @foreach ($infoBlocks as $item)
                  <span class="info-item">
                    <span class="material-symbols-rounded">{{ $item->icon ?? 'info' }}</span>
                    {{ $item->content }}
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
