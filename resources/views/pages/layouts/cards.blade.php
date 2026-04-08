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
          $blocks      = $entry->blocks;
          $badgeBlocks = $blocks->where('type', 'badge');
          $textBlocks  = $blocks->where('type', 'text')->values();
          $desc        = $textBlocks->first()?->content;
          $highlights  = $textBlocks->skip(1)->first()?->content;
        @endphp
        <div class="card">
          @if ($entry->cover_image)
            <div class="card__img">
              <img src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" loading="lazy" />
            </div>
          @endif
          <div class="card__body">
            @if ($badgeBlocks->isNotEmpty())
              <div class="card__meta">
                @foreach ($badgeBlocks as $badge)
                  <span class="badge badge--{{ $badge->color ?? 'gray' }}">{{ $badge->content }}</span>
                @endforeach
              </div>
            @endif
            <h3 class="card__title">{{ $entry->title }}</h3>
            @if ($desc)
              @php
                $descNorm  = preg_replace('/\n{3,}/', "\n\n", str_replace(["\r\n", "\r"], "\n", $desc));
                $descLines = explode("\n", $descNorm);
                $inList    = false;
                $lastEmpty = false;
              @endphp
              @foreach ($descLines as $line)
                @php $line = trim($line); @endphp
                @if (str_starts_with($line, '- '))
                  @if (!$inList) <ul class="card__text-list"> @php $inList = true; @endphp @endif
                  <li>{{ substr($line, 2) }}</li>
                @else
                  @if ($inList) </ul> @php $inList = false; @endphp @endif
                  @if ($line !== '')
                    @php $lastEmpty = false; @endphp
                    <p class="card__text">{{ $line }}</p>
                  @elseif (!$lastEmpty)
                    @php $lastEmpty = true; @endphp
                    <br>
                  @endif
                @endif
              @endforeach
              @if ($inList) </ul> @endif
            @endif
            @if ($highlights)
              @php
                $hlLines   = explode("\n", $highlights);
                $firstLine = trim($hlLines[0] ?? '');
                $hlHeading = (!str_starts_with($firstLine, '- ') && $firstLine !== '') ? $firstLine : null;
                $hlBody    = $hlHeading ? array_slice($hlLines, 1) : $hlLines;
              @endphp
              <div class="card__highlights">
                @if ($hlHeading)
                  <h4>{{ $hlHeading }}</h4>
                @endif
                @php $listItems = array_filter($hlBody, fn($l) => str_starts_with(trim($l), '- ')); @endphp
                @if (count($listItems))
                  <ul>
                    @foreach ($hlBody as $line)
                      @php $line = trim($line); @endphp
                      @if (str_starts_with($line, '- '))
                        <li>{{ substr($line, 2) }}</li>
                      @elseif ($line !== '')
                        </ul><p>{{ $line }}</p><ul>
                      @endif
                    @endforeach
                  </ul>
                @else
                  @foreach ($hlBody as $line)
                    @php $line = trim($line); @endphp
                    @if ($line !== '')
                      <p>{{ $line }}</p>
                    @endif
                  @endforeach
                @endif
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>

  </div>
</section>
