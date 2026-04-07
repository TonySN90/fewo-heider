{{-- Layout: cards (wandern, schloesser-parks) --}}
<section class="content">
  <div class="container">

    @php $intro = $entries->first()?->blocks->firstWhere('type', 'heading'); @endphp
    @if ($intro)
      <div class="content__intro">
        <h2>{{ $intro->content }}</h2>
        <div class="divider"></div>
        @php $introText = $entries->first()?->blocks->firstWhere('type', 'text'); @endphp
        @if ($introText)
          <p>{{ $introText->content }}</p>
        @endif
      </div>
    @endif

    <div class="cards">
      @foreach ($entries as $entry)
        @php
          $blocks   = $entry->blocks;
          $textBlocks = $blocks->where('type', 'text')->values();
          $desc     = $textBlocks->first()?->content;
          $highlights = $textBlocks->skip(1)->first()?->content;
          $meta       = $textBlocks->last()?->content; // z.B. "Schwierigkeit: ... · km"
          // Badges aus Meta-Zeile parsen (enthält "·"-Trenner)
          $badges = $meta ? array_map('trim', explode('·', $meta)) : [];
        @endphp
        <div class="card">
          @if ($entry->cover_image)
            <div class="card__img">
              <img src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" loading="lazy" />
            </div>
          @endif
          <div class="card__body">
            @if (count($badges) > 1)
              <div class="card__meta">
                @foreach ($badges as $badge)
                  <span class="badge badge--{{ $loop->first ? 'green' : 'blue' }}">{{ $badge }}</span>
                @endforeach
              </div>
            @endif
            <h3 class="card__title">{{ $entry->title }}</h3>
            @if ($desc)
              <p class="card__text">{{ $desc }}</p>
            @endif
            @if ($highlights && str_contains($highlights, '•'))
              <div class="card__highlights">
                <h4>Highlights</h4>
                <ul>
                  @foreach (array_filter(array_map('trim', explode('•', $highlights))) as $item)
                    <li>{{ $item }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>

  </div>
</section>
