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
          $infoBlocks = $blocks->where('type', 'info')->values();
          $desc       = $textBlocks->first()?->content;
        @endphp
        <div class="place {{ $entry->image_position === 'right' ? 'place--img-right' : '' }}">
          @if ($entry->cover_image)
            <div class="place__img">
              <img src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" loading="lazy" />
            </div>
          @endif
          <div class="place__body">
            @if ($infoBlocks->isNotEmpty())
              <div class="place__info">
                @foreach ($infoBlocks as $item)
                  <span class="info-item">
                    <span class="material-symbols-rounded">{{ $item->icon ?? 'location_on' }}</span>
                    {{ $item->content }}
                  </span>
                @endforeach
              </div>
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
