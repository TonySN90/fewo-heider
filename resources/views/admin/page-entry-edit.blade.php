@extends('layouts.admin')

@section('title', $entry->title . ' bearbeiten')

@section('content')
  <div class="page-header">
    <div>
      <a href="{{ route('admin.pages.entries', $page) }}" class="back-link">
        <span class="material-symbols-rounded">arrow_back</span>
        Zurück zu {{ $page->title }}
      </a>
      <h1>{{ $entry->title }}</h1>
    </div>
    <div class="modal-lang-toggle" id="global-lang-toggle">
      <button type="button" class="lang-toggle-btn lang-toggle-btn--active" data-lang="de">DE</button>
      <button type="button" class="lang-toggle-btn" data-lang="en">EN</button>
    </div>
  </div>


  {{-- URL-Info --}}
  @php
    $group = $page->group;
    $urlPath = $group
      ? '/' . $group->slug . '/' . $page->slug . '/' . $entry->slug
      : '/' . $page->slug . '/' . $entry->slug;
  @endphp
  <p style="font-size:.8rem;color:#aaa;margin-bottom:1.5rem">
    <span class="material-symbols-rounded" style="font-size:.9rem;vertical-align:middle">link</span>
    {{ $urlPath }}
  </p>

  {{-- Card-Vorschau mit Inline-Editing (nur bei cards-Layout) --}}
  @if ($page->layout === 'cards')
  @php
    $previewTextBlocks  = $entry->blocks->where('type', 'text')->values();
    $previewDesc        = $previewTextBlocks->first()?->content;
    $previewHighlights  = $previewTextBlocks->skip(1)->first()?->content;
    $previewDescBlock   = $previewTextBlocks->first();
    $previewHlBlock     = $previewTextBlocks->skip(1)->first();
    $previewDescEnBlock = $entry->blocks->firstWhere('type', 'text_en');
    $previewHlEnBlock   = $entry->blocks->where('type', 'text_en')->values()->skip(1)->first();
  @endphp
  <div class="table-card" style="margin-top:1.5rem">
    <div class="table-card__header">
      <h2>Vorschau</h2>
      <span style="font-size:.8rem;color:#aaa">
        <span class="material-symbols-rounded" style="font-size:.9rem;vertical-align:middle">edit</span>
        Klicke direkt in die Vorschau zum Bearbeiten
      </span>
    </div>
    <div class="entry-preview">
      <div class="card">
        <div class="card__img card__img--clickable" id="preview-img-wrap"
             onclick="document.getElementById('preview-img-upload').click()"
             title="Klicken um Bild zu ändern">
          @if ($entry->cover_image)
            <img id="preview-img" src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" />
          @else
            <div id="preview-img-placeholder" class="card__img-placeholder">
              <span class="material-symbols-rounded">add_photo_alternate</span>
              <span>Bild hochladen</span>
            </div>
          @endif
          <div class="card__img-overlay">
            <span class="material-symbols-rounded">photo_camera</span>
          </div>
        </div>
        <input type="file" id="preview-img-upload"
               accept="image/*" style="display:none"
               data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}" />
        <div class="card__body">

          {{-- Badges: Text + Farbe inline editierbar --}}
          <div class="card__meta" id="preview-badges">
            @foreach ($entry->blocks->where('type', 'badge') as $b)
              <span class="badge-wrap">
                <span class="badge-drag-handle" title="Verschieben">⠿</span>
                <span class="badge badge--{{ $b->color ?? 'gray' }} preview-editable"
                      contenteditable="true"
                      draggable="false"
                      data-type="block"
                      data-block-id="{{ $b->id }}"
                      data-color="{{ $b->color ?? 'gray' }}"
                      data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $b]) }}">{{ $b->content }}</span>
                <button type="button" class="badge-delete-btn"
                        data-delete-url="{{ route('admin.pages.blocks.destroy', [$page, $entry, $b]) }}"
                        title="Badge löschen">×</button>
              </span>
            @endforeach
            <button type="button" id="badge-add-btn" class="badge-add-btn" title="Badge hinzufügen">
              <span class="material-symbols-rounded">add</span>
            </button>
          </div>

          {{-- Farb-Picker + Neu-Badge Popup --}}
          <div id="badge-color-picker" class="badge-color-picker" style="display:none">
            <button type="button" data-color="green"  class="badge badge--green">Grün</button>
            <button type="button" data-color="blue"   class="badge badge--blue">Blau</button>
            <button type="button" data-color="orange" class="badge badge--orange">Orange</button>
            <button type="button" data-color="gray"   class="badge badge--gray">Grau</button>
          </div>

          {{-- Neuer Badge: Inline-Input (erscheint beim Klick auf +) --}}
          <div id="badge-new-form" class="badge-new-form" style="display:none">
            <input type="text" id="badge-new-input" placeholder="Badge-Text" maxlength="60" />
            <div class="badge-new-form__colors">
              <button type="button" data-color="green"  class="badge badge--green">Grün</button>
              <button type="button" data-color="blue"   class="badge badge--blue">Blau</button>
              <button type="button" data-color="orange" class="badge badge--orange">Orange</button>
              <button type="button" data-color="gray"   class="badge badge--gray">Grau</button>
            </div>
            <div class="badge-new-form__actions">
              <button type="button" id="badge-new-save" class="btn btn-save btn-save--sm">Hinzufügen</button>
              <button type="button" id="badge-new-cancel" class="btn btn-cancel">Abbrechen</button>
            </div>
          </div>

          {{-- Titel: inline editierbar (DE) --}}
          <h3 class="card__title preview-editable"
              contenteditable="true"
              id="card-title"
              data-lang="de"
              data-type="entry-title"
              data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}">{{ $entry->title }}</h3>

          {{-- Titel EN --}}
          <h3 class="card__title preview-editable"
              contenteditable="true"
              id="card-title-en"
              data-lang="en"
              data-field="title_en"
              style="display:none"
              data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}">{{ $entry->title_en ?? '' }}</h3>

          {{-- Beschreibung DE: inline editierbar --}}
          @if ($previewDescBlock)
            <p class="card__text preview-editable preview-editable--multiline"
               contenteditable="true"
               id="card-desc"
               data-lang="de"
               data-type="block"
               data-block-id="{{ $previewDescBlock->id }}"
               data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $previewDescBlock]) }}">{{ $previewDesc }}</p>
          @endif

          {{-- Beschreibung EN --}}
          <p class="card__text preview-editable preview-editable--multiline"
             contenteditable="true"
             id="card-desc-en"
             data-lang="en"
             data-field="text_en"
             style="display:none"
             data-url="{{ $previewDescEnBlock ? route('admin.pages.blocks.update', [$page, $entry, $previewDescEnBlock]) : '' }}">{{ $previewDescEnBlock?->content ?? '' }}</p>

          {{-- Highlights DE: Titel + Body jeweils eigenes contenteditable --}}
          @if ($previewHlBlock)
            @php
              $hlLines   = explode("\n", $previewHlBlock->content);
              $firstLine = trim($hlLines[0] ?? '');
              $hlHeading = (!str_starts_with($firstLine, '- ') && $firstLine !== '') ? $firstLine : 'Highlights';
              $hlBody    = implode("\n", array_slice($hlLines, $hlHeading !== 'Highlights' || count($hlLines) > 1 ? 1 : 0));
            @endphp
            <div class="card__highlights" data-lang="de">
              <h4 class="preview-editable"
                  contenteditable="true"
                  id="card-hl-title"
                  data-type="hl-title"
                  data-block-id="{{ $previewHlBlock->id }}"
                  data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $previewHlBlock]) }}">{{ $hlHeading }}</h4>
              <pre class="preview-editable preview-editable--multiline"
                   contenteditable="true"
                   id="card-hl-body"
                   data-type="hl-body"
                   data-block-id="{{ $previewHlBlock->id }}"
                   data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $previewHlBlock]) }}">{{ $hlBody }}</pre>
            </div>
          @endif

          {{-- Highlights EN --}}
          @php
            $hlEnLines   = explode("\n", $previewHlEnBlock?->content ?? '');
            $hlEnFirst   = trim($hlEnLines[0] ?? '');
            $hlEnHeading = (!str_starts_with($hlEnFirst, '- ') && $hlEnFirst !== '') ? $hlEnFirst : 'Highlights';
            $hlEnBody    = implode("\n", array_slice($hlEnLines, $hlEnHeading !== 'Highlights' || count($hlEnLines) > 1 ? 1 : 0));
          @endphp
          <div class="card__highlights" data-lang="en" style="display:none">
            <h4 class="preview-editable"
                contenteditable="true"
                id="card-hl-title-en"
                data-field="hl-title-en"
                data-url="{{ $previewHlEnBlock ? route('admin.pages.blocks.update', [$page, $entry, $previewHlEnBlock]) : '' }}">{{ $hlEnHeading }}</h4>
            <pre class="preview-editable preview-editable--multiline"
                 contenteditable="true"
                 id="card-hl-body-en"
                 data-field="hl-body-en"
                 data-url="{{ $previewHlEnBlock ? route('admin.pages.blocks.update', [$page, $entry, $previewHlEnBlock]) : '' }}">{{ $hlEnBody }}</pre>
          </div>

        </div>
      </div>
    </div>

    <div class="hero-edit-actions">
      <button type="button" id="card-save" class="btn btn-save"
              data-entry-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}"
              data-desc-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
              data-desc-update-url="{{ $previewDescBlock ? route('admin.pages.blocks.update', [$page, $entry, $previewDescBlock]) : '' }}"
              data-desc-has-block="{{ $previewDescBlock ? '1' : '0' }}"
              data-hl-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
              data-hl-update-url="{{ $previewHlBlock ? route('admin.pages.blocks.update', [$page, $entry, $previewHlBlock]) : '' }}"
              data-hl-has-block="{{ $previewHlBlock ? '1' : '0' }}"
              data-desc-en-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
              data-desc-en-update-url="{{ $previewDescEnBlock ? route('admin.pages.blocks.update', [$page, $entry, $previewDescEnBlock]) : '' }}"
              data-desc-en-has-block="{{ $previewDescEnBlock ? '1' : '0' }}"
              data-hl-en-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
              data-hl-en-update-url="{{ $previewHlEnBlock ? route('admin.pages.blocks.update', [$page, $entry, $previewHlEnBlock]) : '' }}"
              data-hl-en-has-block="{{ $previewHlEnBlock ? '1' : '0' }}">
        Speichern
      </button>
    </div>

  </div>
  @endif

  {{-- Route-Vorschau mit Inline-Editing (nur bei route-Layout) --}}
  @if ($page->layout === 'route')
  @php
    $routeTextBlocks  = $entry->blocks->where('type', 'text')->values();
    $routeDescBlock   = $routeTextBlocks->first();
    $routeStatsBlock  = $routeTextBlocks->skip(1)->first();
    $routeDescEnBlock = $entry->blocks->firstWhere('type', 'text_en');
    $routeLengthVal   = ''; $routeLengthLabel   = 'Gesamtlänge';
    $routeDiff        = 'leicht';
    $routeDurationVal = ''; $routeDurationLabel = 'Dauer';
    if ($routeStatsBlock) {
      foreach (array_map('trim', explode('·', $routeStatsBlock->content)) as $part) {
        if (!str_contains($part, ':')) continue;
        [$lbl, $val] = array_map('trim', explode(':', $part, 2));
        if (str_contains(strtolower($lbl), 'schwierigkeit')) {
          $routeDiff = $val;
        } elseif (str_contains(strtolower($lbl), 'nge') || str_contains(strtolower($lbl), 'gesamtl')) {
          $routeLengthVal   = $val;
          $routeLengthLabel = $lbl;
        } else {
          $routeDurationVal   = $val;
          $routeDurationLabel = $lbl;
        }
      }
    }
  @endphp

  <div class="table-card" style="margin-top:1.5rem">
    <div class="table-card__header">
      <h2>Vorschau</h2>
      <span style="font-size:.8rem;color:#aaa">
        <span class="material-symbols-rounded" style="font-size:.9rem;vertical-align:middle">edit</span>
        Klicke direkt in die Vorschau zum Bearbeiten
      </span>
    </div>
    <div class="entry-preview entry-preview--route">

      <div class="route route--edit">
        <div class="route__body">
          <h2 class="route__title"
              contenteditable="true"
              id="route-title"
              data-lang="de">{{ $entry->title }}</h2>

          <h2 class="route__title"
              contenteditable="true"
              id="route-title-en"
              data-lang="en"
              data-field="title_en"
              style="display:none">{{ $entry->title_en ?? '' }}</h2>

          <p class="route__text"
             contenteditable="true"
             id="route-desc"
             data-lang="de">{{ $routeDescBlock?->content ?? '' }}</p>

          <p class="route__text"
             contenteditable="true"
             id="route-desc-en"
             data-lang="en"
             data-field="text_en"
             style="display:none">{{ $routeDescEnBlock?->content ?? '' }}</p>

          @php
            $diffClass = match($routeDiff) {
              'moderat' => 'diff--medium',
              'schwer'  => 'diff--hard',
              default   => 'diff--easy',
            };
          @endphp
          <div class="route__diff-edit">
            <select id="route-diff" class="route-diff-select route-diff-select--{{ $diffClass }}">
              <option value="leicht"  @selected($routeDiff === 'leicht')>leicht</option>
              <option value="moderat" @selected($routeDiff === 'moderat')>moderat</option>
              <option value="schwer"  @selected($routeDiff === 'schwer')>schwer</option>
            </select>
          </div>
        </div>

        <div class="route__stats">
          <div class="stat">
            <p class="stat__value"
               contenteditable="true"
               id="route-length-val">{{ $routeLengthVal ?: '–' }}</p>
            <p class="stat__label"
               contenteditable="true"
               id="route-length-label">{{ $routeLengthLabel }}</p>
          </div>
          <div class="stat">
            <p class="stat__value"
               contenteditable="true"
               id="route-duration-val">{{ $routeDurationVal ?: '–' }}</p>
            <p class="stat__label"
               contenteditable="true"
               id="route-duration-label">{{ $routeDurationLabel }}</p>
          </div>
        </div>
      </div>

      <div class="route-edit-actions">
        <button type="button" id="route-stats-save" class="btn btn-save"
                data-entry-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}"
                data-desc-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
                data-desc-update-url="{{ $routeDescBlock ? route('admin.pages.blocks.update', [$page, $entry, $routeDescBlock]) : '' }}"
                data-desc-has-block="{{ $routeDescBlock ? '1' : '0' }}"
                data-stats-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
                data-stats-update-url="{{ $routeStatsBlock ? route('admin.pages.blocks.update', [$page, $entry, $routeStatsBlock]) : '' }}"
                data-stats-has-block="{{ $routeStatsBlock ? '1' : '0' }}"
                data-desc-en-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
                data-desc-en-update-url="{{ $routeDescEnBlock ? route('admin.pages.blocks.update', [$page, $entry, $routeDescEnBlock]) : '' }}"
                data-desc-en-has-block="{{ $routeDescEnBlock ? '1' : '0' }}">
          Speichern
        </button>
      </div>

    </div>
  </div>
  @endif

  {{-- Place-List-Vorschau mit Inline-Editing --}}
  {{-- Feature-Blöcke Editor --}}
  @if ($page->layout === 'feature')
  @php
    $editTextBlocks  = $entry->blocks->where('type', 'text')->values();
    $descBlock       = $editTextBlocks->first();
    $desc2Block      = $editTextBlocks->skip(1)->first();
    $descEnBlock     = $entry->blocks->firstWhere('type', 'text_en');
    $desc2EnBlock    = $entry->blocks->where('type', 'text_en')->values()->skip(1)->first();
    $infoBlocks      = $entry->blocks->where('type', 'info')->values();
    $icons           = \App\Models\Icon::forSelect();
    $defaultIcon     = 'info';
    $infoFirst       = $infoBlocks->isNotEmpty() && $editTextBlocks->isNotEmpty()
                         ? ($infoBlocks->min('sort_order') < $editTextBlocks->min('sort_order'))
                         : false;
    $textBlockIds    = collect([$descBlock, $desc2Block])->filter()->pluck('id')->join(',');
    $imgClass        = 'feature__img';
    $bodyClass       = 'feature__body';
    $cardClass       = 'feature feature--edit';
    $previewClass    = 'entry-preview--feature';
    $titleClass      = 'feature__title';
    $textClass       = 'feature__text';
  @endphp

  <div class="table-card" style="margin-top:1.5rem">
    <div class="table-card__header">
      <h2>Vorschau</h2>
      <span style="font-size:.8rem;color:#aaa">
        <span class="material-symbols-rounded" style="font-size:.9rem;vertical-align:middle">edit</span>
        Klicke direkt in die Vorschau zum Bearbeiten
      </span>
    </div>
    <div class="entry-preview {{ $previewClass }}">

      <div class="{{ $cardClass }}" id="place-card">
        <div class="{{ $imgClass }} feature__img--draggable" id="preview-img-wrap"
             data-position="{{ $entry->image_position ?? 'left' }}"
             data-entry-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}">
          <div class="feature__img-drag-hint">
            <span class="material-symbols-rounded">swap_horiz</span>
          </div>
          <div class="feature__img-click" onclick="document.getElementById('preview-img-upload').click()" title="Klicken um Bild zu ändern">
            @if ($entry->cover_image)
              <img id="preview-img" src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" />
            @else
              <div id="preview-img-placeholder" class="card__img-placeholder">
                <span class="material-symbols-rounded">add_photo_alternate</span>
                <span>Bild hochladen</span>
              </div>
            @endif
            <div class="card__img-overlay">
              <span class="material-symbols-rounded">photo_camera</span>
            </div>
          </div>
        </div>
        <input type="file" id="preview-img-upload" accept="image/*" style="display:none"
               data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}" />

        <div class="{{ $bodyClass }}">

          {{-- Draggable Body-Blocks: Info-Section & Text-Section --}}
          <div class="body-blocks" id="body-blocks"
               data-reorder-url="{{ route('admin.pages.blocks.reorder', [$page, $entry]) }}">

            {{-- Info-Section --}}
            @if ($infoFirst)
              @include('admin.partials.entry-info-section', ['infoBlocks' => $infoBlocks, 'icons' => $icons, 'defaultIcon' => $defaultIcon, 'page' => $page, 'entry' => $entry])
            @endif

            {{-- Text-Section --}}
            <div class="body-block" data-type="text-section" id="body-block-text"
                 data-block-ids="{{ $textBlockIds }}">
              <div class="body-block__header">
                <span class="body-block__drag-handle" title="Abschnitt verschieben">⠿</span>
                <span class="body-block__label">Text</span>
                <button type="button" class="img-side-toggle" id="img-side-toggle"
                        title="Bildseite wechseln"
                        data-entry-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}"
                        data-position="{{ $entry->image_position ?? 'left' }}">
                  <span class="material-symbols-rounded">swap_horiz</span>
                  <span class="img-side-toggle__label">Bild {{ ($entry->image_position ?? 'left') === 'right' ? 'links' : 'rechts' }}</span>
                </button>
              </div>
              <h2 class="{{ $titleClass }}"
                  contenteditable="true"
                  id="place-title"
                  data-lang="de">{{ $entry->title }}</h2>
              <h2 class="{{ $titleClass }}"
                  contenteditable="true"
                  id="place-title-en"
                  data-lang="en"
                  data-field="title_en"
                  style="display:none">{{ $entry->title_en ?? '' }}</h2>
              <p class="{{ $textClass }}"
                 contenteditable="true"
                 id="place-desc"
                 data-lang="de">{{ $descBlock?->content ?? '' }}</p>
              <p class="{{ $textClass }}"
                 contenteditable="true"
                 id="place-desc-en"
                 data-lang="en"
                 data-field="text_en"
                 style="display:none">{{ $descEnBlock?->content ?? '' }}</p>
              @if ($desc2Block)
              <p class="{{ $textClass }}"
                 contenteditable="true"
                 id="place-desc2"
                 data-lang="de"
                 style="margin-top:.75rem">{{ $desc2Block->content }}</p>
              @endif
              <p class="{{ $textClass }}"
                 contenteditable="true"
                 id="place-desc2-en"
                 data-lang="en"
                 data-field="text_en_2"
                 style="display:none;margin-top:.75rem">{{ $desc2EnBlock?->content ?? '' }}</p>
            </div>

            {{-- Info-Section (falls nach Text) --}}
            @if (!$infoFirst)
              @include('admin.partials.entry-info-section', ['infoBlocks' => $infoBlocks, 'icons' => $icons, 'defaultIcon' => $defaultIcon, 'page' => $page, 'entry' => $entry])
            @endif

          </div>

        </div>
      </div>

      <div class="place-edit-actions">
        <button type="button" id="place-save" class="btn btn-save"
                data-entry-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}"
                data-desc-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
                data-desc-update-url="{{ $descBlock ? route('admin.pages.blocks.update', [$page, $entry, $descBlock]) : '' }}"
                data-desc-has-block="{{ $descBlock ? '1' : '0' }}"
                data-desc2-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
                data-desc2-update-url="{{ $desc2Block ? route('admin.pages.blocks.update', [$page, $entry, $desc2Block]) : '' }}"
                data-desc2-has-block="{{ $desc2Block ? '1' : '0' }}"
                data-desc-en-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
                data-desc-en-update-url="{{ $descEnBlock ? route('admin.pages.blocks.update', [$page, $entry, $descEnBlock]) : '' }}"
                data-desc-en-has-block="{{ $descEnBlock ? '1' : '0' }}"
                data-desc2-en-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
                data-desc2-en-update-url="{{ $desc2EnBlock ? route('admin.pages.blocks.update', [$page, $entry, $desc2EnBlock]) : '' }}"
                data-desc2-en-has-block="{{ $desc2EnBlock ? '1' : '0' }}">
          Speichern
        </button>
      </div>

    </div>
  </div>
  @endif

  {{-- Hero + Karten-Grid Editor --}}
  @if ($page->layout === 'hero-feature')
  @php
    $isHeroEntry = $page->entries->first()?->id === $entry->id;
  @endphp

  @if ($isHeroEntry)
  {{-- Hero-Eintrag --}}
  @php
    $heroTextBlocks   = $entry->blocks->where('type', 'text')->values();
    $heroTextBlock    = $heroTextBlocks->first();
    $heroFactsBlock   = $heroTextBlocks->skip(1)->first();
    $heroTextEnBlock  = $entry->blocks->firstWhere('type', 'text_en');
  @endphp
  <div class="table-card" style="margin-top:1.5rem">
    <div class="table-card__header">
      <h2>Vorschau</h2>
      <span style="font-size:.8rem;color:#aaa">
        <span class="material-symbols-rounded" style="font-size:.9rem;vertical-align:middle">edit</span>
        Klicke direkt in die Vorschau zum Bearbeiten
      </span>
    </div>
    <div class="entry-preview entry-preview--hero">
      <div class="hero-feature--edit" id="hero-card">

        <div class="hero-feature__img hero-feature__img--clickable" id="preview-img-wrap"
             onclick="document.getElementById('preview-img-upload').click()"
             title="Klicken um Bild zu ändern">
          @if ($entry->cover_image)
            <img id="preview-img" src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" />
          @else
            <div id="preview-img-placeholder" class="card__img-placeholder">
              <span class="material-symbols-rounded">add_photo_alternate</span>
              <span>Bild hochladen</span>
            </div>
          @endif
          <div class="card__img-overlay">
            <span class="material-symbols-rounded">photo_camera</span>
          </div>
        </div>
        <input type="file" id="preview-img-upload"
               accept="image/*" style="display:none"
               data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}" />

        <div class="hero-feature__body">
          <h2 class="hero-feature__title preview-editable"
              contenteditable="true"
              id="hero-title"
              data-lang="de"
              data-type="entry-title"
              data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}">{{ $entry->title }}</h2>

          <h2 class="hero-feature__title preview-editable"
              contenteditable="true"
              id="hero-title-en"
              data-lang="en"
              data-field="title_en"
              style="display:none"
              data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}">{{ $entry->title_en ?? '' }}</h2>

          <p class="hero-feature__text preview-editable preview-editable--multiline"
             contenteditable="true"
             id="hero-text"
             data-lang="de">{{ $heroTextBlock?->content ?? '' }}</p>

          <p class="hero-feature__text preview-editable preview-editable--multiline"
             contenteditable="true"
             id="hero-text-en"
             data-lang="en"
             data-field="text_en"
             style="display:none">{{ $heroTextEnBlock?->content ?? '' }}</p>

          @php
            $factsRaw     = $heroFactsBlock?->content ?? '';
            $factsPreview = [];
            foreach (array_filter(array_map('trim', explode('·', $factsRaw))) as $part) {
              if (str_contains($part, ':')) {
                [$fl, $fv] = array_map('trim', explode(':', $part, 2));
                $factsPreview[] = ['label' => $fl, 'value' => $fv];
              }
            }
          @endphp
          <div class="hero-feature__facts" id="hero-facts-grid">
            @foreach ($factsPreview as $i => $f)
              <div class="fact hero-fact">
                <p class="fact__label"
                   contenteditable="true"
                   id="hero-fact-label-{{ $i }}">{{ $f['label'] }}</p>
                <p class="fact__value"
                   contenteditable="true"
                   id="hero-fact-value-{{ $i }}">{{ $f['value'] }}</p>
              </div>
            @endforeach
            <div class="fact hero-fact hero-fact--add">
              <p class="fact__label"
                 contenteditable="true"
                 id="hero-fact-label-new"
                 data-placeholder="Bezeichnung"></p>
              <p class="fact__value"
                 contenteditable="true"
                 id="hero-fact-value-new"
                 data-placeholder="Wert"></p>
            </div>
          </div>
        </div>

      </div>

      <div class="hero-edit-actions">
        <button type="button" id="hero-save" class="btn btn-save"
                data-entry-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}"
                data-text-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
                data-text-update-url="{{ $heroTextBlock ? route('admin.pages.blocks.update', [$page, $entry, $heroTextBlock]) : '' }}"
                data-text-has-block="{{ $heroTextBlock ? '1' : '0' }}"
                data-facts-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
                data-facts-update-url="{{ $heroFactsBlock ? route('admin.pages.blocks.update', [$page, $entry, $heroFactsBlock]) : '' }}"
                data-facts-has-block="{{ $heroFactsBlock ? '1' : '0' }}"
                data-text-en-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
                data-text-en-update-url="{{ $heroTextEnBlock ? route('admin.pages.blocks.update', [$page, $entry, $heroTextEnBlock]) : '' }}"
                data-text-en-has-block="{{ $heroTextEnBlock ? '1' : '0' }}">
          Speichern
        </button>
      </div>
    </div>
  </div>

  @else
  {{-- Karten-Eintrag im hero-feature-Grid --}}
  @php
    $hfTextBlocks   = $entry->blocks->where('type', 'text')->values();
    $hfDescBlock    = $hfTextBlocks->first();
    $hfHlBlock      = $hfTextBlocks->skip(1)->first();
    $hfDescEnBlock  = $entry->blocks->firstWhere('type', 'text_en');
    $hfHlEnBlock    = $entry->blocks->where('type', 'text_en')->values()->skip(1)->first();
  @endphp
  <div class="table-card" style="margin-top:1.5rem">
    <div class="table-card__header">
      <h2>Vorschau</h2>
      <span style="font-size:.8rem;color:#aaa">
        <span class="material-symbols-rounded" style="font-size:.9rem;vertical-align:middle">edit</span>
        Klicke direkt in die Vorschau zum Bearbeiten
      </span>
    </div>
    <div class="entry-preview">
      <div class="card">
        <div class="card__img card__img--clickable" id="preview-img-wrap"
             onclick="document.getElementById('preview-img-upload').click()"
             title="Klicken um Bild zu ändern">
          @if ($entry->cover_image)
            <img id="preview-img" src="{{ Storage::url($entry->cover_image) }}" alt="{{ $entry->title }}" />
          @else
            <div id="preview-img-placeholder" class="card__img-placeholder">
              <span class="material-symbols-rounded">add_photo_alternate</span>
              <span>Bild hochladen</span>
            </div>
          @endif
          <div class="card__img-overlay">
            <span class="material-symbols-rounded">photo_camera</span>
          </div>
        </div>
        <input type="file" id="preview-img-upload"
               accept="image/*" style="display:none"
               data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}" />
        <div class="card__body">

          <div class="card__meta" id="preview-badges">
            @foreach ($entry->blocks->where('type', 'badge') as $b)
              <span class="badge-wrap">
                <span class="badge-drag-handle" title="Verschieben">⠿</span>
                <span class="badge badge--{{ $b->color ?? 'gray' }} preview-editable"
                      contenteditable="true"
                      draggable="false"
                      data-type="block"
                      data-block-id="{{ $b->id }}"
                      data-color="{{ $b->color ?? 'gray' }}"
                      data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $b]) }}">{{ $b->content }}</span>
                <button type="button" class="badge-delete-btn"
                        data-delete-url="{{ route('admin.pages.blocks.destroy', [$page, $entry, $b]) }}"
                        title="Badge löschen">×</button>
              </span>
            @endforeach
            <button type="button" id="badge-add-btn" class="badge-add-btn" title="Badge hinzufügen">
              <span class="material-symbols-rounded">add</span>
            </button>
          </div>

          <div id="badge-color-picker" class="badge-color-picker" style="display:none">
            <button type="button" data-color="green"  class="badge badge--green">Grün</button>
            <button type="button" data-color="blue"   class="badge badge--blue">Blau</button>
            <button type="button" data-color="orange" class="badge badge--orange">Orange</button>
            <button type="button" data-color="gray"   class="badge badge--gray">Grau</button>
          </div>

          <div id="badge-new-form" class="badge-new-form" style="display:none">
            <input type="text" id="badge-new-input" placeholder="Badge-Text" maxlength="60" />
            <div class="badge-new-form__colors">
              <button type="button" data-color="green"  class="badge badge--green">Grün</button>
              <button type="button" data-color="blue"   class="badge badge--blue">Blau</button>
              <button type="button" data-color="orange" class="badge badge--orange">Orange</button>
              <button type="button" data-color="gray"   class="badge badge--gray">Grau</button>
            </div>
            <div class="badge-new-form__actions">
              <button type="button" id="badge-new-save" class="btn btn-save btn-save--sm">Hinzufügen</button>
              <button type="button" id="badge-new-cancel" class="btn btn-cancel">Abbrechen</button>
            </div>
          </div>

          <h3 class="card__title preview-editable"
              contenteditable="true"
              id="hf-card-title"
              data-lang="de"
              data-type="entry-title"
              data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}">{{ $entry->title }}</h3>

          <h3 class="card__title preview-editable"
              contenteditable="true"
              id="hf-card-title-en"
              data-lang="en"
              data-field="title_en"
              style="display:none"
              data-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}">{{ $entry->title_en ?? '' }}</h3>

          @if ($hfDescBlock)
            <p class="card__text preview-editable preview-editable--multiline"
               contenteditable="true"
               id="hf-card-desc"
               data-lang="de"
               data-type="block"
               data-block-id="{{ $hfDescBlock->id }}"
               data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $hfDescBlock]) }}">{{ $hfDescBlock->content }}</p>
          @endif

          <p class="card__text preview-editable preview-editable--multiline"
             contenteditable="true"
             id="hf-card-desc-en"
             data-lang="en"
             data-field="text_en"
             style="display:none">{{ $hfDescEnBlock?->content ?? '' }}</p>

          @if ($hfHlBlock)
            @php
              $hfHlLines   = explode("\n", $hfHlBlock->content);
              $hfFirstLine = trim($hfHlLines[0] ?? '');
              $hfHlHeading = (!str_starts_with($hfFirstLine, '- ') && $hfFirstLine !== '') ? $hfFirstLine : 'Highlights';
              $hfHlBody    = implode("\n", array_slice($hfHlLines, $hfHlHeading !== 'Highlights' || count($hfHlLines) > 1 ? 1 : 0));
            @endphp
            <div class="card__highlights" data-lang="de">
              <h4 class="preview-editable"
                  contenteditable="true"
                  id="hf-card-hl-title"
                  data-type="hl-title"
                  data-block-id="{{ $hfHlBlock->id }}"
                  data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $hfHlBlock]) }}">{{ $hfHlHeading }}</h4>
              <pre class="preview-editable preview-editable--multiline"
                   contenteditable="true"
                   id="hf-card-hl-body"
                   data-type="hl-body"
                   data-block-id="{{ $hfHlBlock->id }}"
                   data-url="{{ route('admin.pages.blocks.update', [$page, $entry, $hfHlBlock]) }}">{{ $hfHlBody }}</pre>
            </div>
          @endif

          @php
            $hfHlEnLines   = explode("\n", $hfHlEnBlock?->content ?? '');
            $hfHlEnFirst   = trim($hfHlEnLines[0] ?? '');
            $hfHlEnHeading = (!str_starts_with($hfHlEnFirst, '- ') && $hfHlEnFirst !== '') ? $hfHlEnFirst : 'Highlights';
            $hfHlEnBody    = implode("\n", array_slice($hfHlEnLines, $hfHlEnHeading !== 'Highlights' || count($hfHlEnLines) > 1 ? 1 : 0));
          @endphp
          <div class="card__highlights" data-lang="en" style="display:none">
            <h4 class="preview-editable"
                contenteditable="true"
                id="hf-card-hl-title-en"
                data-field="hl-title-en">{{ $hfHlEnHeading }}</h4>
            <pre class="preview-editable preview-editable--multiline"
                 contenteditable="true"
                 id="hf-card-hl-body-en"
                 data-field="hl-body-en">{{ $hfHlEnBody }}</pre>
          </div>

        </div>
      </div>
    </div>

    <div class="hero-edit-actions">
      <button type="button" id="hf-card-save" class="btn btn-save"
              data-entry-url="{{ route('admin.pages.entries.update', [$page, $entry]) }}"
              data-desc-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
              data-desc-update-url="{{ $hfDescBlock ? route('admin.pages.blocks.update', [$page, $entry, $hfDescBlock]) : '' }}"
              data-desc-has-block="{{ $hfDescBlock ? '1' : '0' }}"
              data-hl-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
              data-hl-update-url="{{ $hfHlBlock ? route('admin.pages.blocks.update', [$page, $entry, $hfHlBlock]) : '' }}"
              data-hl-has-block="{{ $hfHlBlock ? '1' : '0' }}"
              data-desc-en-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
              data-desc-en-update-url="{{ $hfDescEnBlock ? route('admin.pages.blocks.update', [$page, $entry, $hfDescEnBlock]) : '' }}"
              data-desc-en-has-block="{{ $hfDescEnBlock ? '1' : '0' }}"
              data-hl-en-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
              data-hl-en-update-url="{{ $hfHlEnBlock ? route('admin.pages.blocks.update', [$page, $entry, $hfHlEnBlock]) : '' }}"
              data-hl-en-has-block="{{ $hfHlEnBlock ? '1' : '0' }}">
        Speichern
      </button>
    </div>

  </div>
  @endif
  @endif


@endsection

@push('scripts')
<script>

// ── Inline-Editing: AJAX blur-save ───────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

  // Bild-Upload via Klick auf Vorschau
  const imgUpload = document.getElementById('preview-img-upload');
  if (imgUpload) {
    imgUpload.addEventListener('change', async () => {
      const file = imgUpload.files[0];
      if (!file) return;
      const wrap = document.getElementById('preview-img-wrap');
      wrap?.classList.add('card__img--uploading');

      // Sofort lokal vorschauen
      const reader = new FileReader();
      reader.onload = e => {
        let img = document.getElementById('preview-img');
        const placeholder = document.getElementById('preview-img-placeholder');
        if (placeholder) {
          placeholder.outerHTML = `<img id="preview-img" src="${e.target.result}" alt="" />`;
        } else if (img) {
          img.src = e.target.result;
        }
      };
      reader.readAsDataURL(file);

      // AJAX-Upload
      const formData = new FormData();
      formData.append('_method', 'PUT');
      formData.append('_token', csrfToken);
      formData.append('title', document.querySelector('[data-type="entry-title"]')?.innerText.trim() ?? '{{ $entry->title }}');
      formData.append('cover_image', file);

      try {
        const res = await fetch(imgUpload.dataset.url, { method: 'POST', body: formData });
        wrap?.classList.remove('card__img--uploading');
        if (res.ok) {
          wrap?.classList.add('card__img--saved');
          setTimeout(() => wrap?.classList.remove('card__img--saved'), 1200);
          // Bild-Vorschau im Formular oben auch aktualisieren
          const formImg = document.querySelector('.section-edit-form img');
          if (formImg) { const r2 = new FileReader(); r2.onload = e => formImg.src = e.target.result; r2.readAsDataURL(file); }
        } else {
          wrap?.classList.add('card__img--error');
          setTimeout(() => wrap?.classList.remove('card__img--error'), 2000);
        }
      } catch {
        wrap?.classList.remove('card__img--uploading');
        wrap?.classList.add('card__img--error');
        setTimeout(() => wrap?.classList.remove('card__img--error'), 2000);
      }
    });
  }

  // ── Badge Farb-Picker ─────────────────────────────────────────────────────
  const picker = document.getElementById('badge-color-picker');
  let activeBadge = null;

  function showPicker(badge) {
    if (!picker) return;
    activeBadge = badge;
    const rect = badge.getBoundingClientRect();
    const wrapRect = badge.closest('.entry-preview').getBoundingClientRect();
    picker.style.top  = (rect.bottom - wrapRect.top + 6) + 'px';
    picker.style.left = (rect.left - wrapRect.left) + 'px';
    picker.style.display = 'flex';
    // Aktive Farbe markieren
    picker.querySelectorAll('button').forEach(btn => {
      btn.classList.toggle('badge-color-picker__active', btn.dataset.color === badge.dataset.color);
    });
  }

  function hidePicker() {
    if (picker) picker.style.display = 'none';
    activeBadge = null;
  }

  picker?.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('mousedown', async e => {
      e.preventDefault(); // blur des Badge verhindern
      const color = btn.dataset.color;
      if (!activeBadge) return;

      const url = activeBadge.dataset.url;
      const body = new FormData();
      body.append('_method', 'PUT');
      body.append('_token', csrfToken);
      body.append('content', activeBadge.innerText.trim());
      body.append('color', color);

      // Sofort visuell aktualisieren
      activeBadge.className = `badge badge--${color} preview-editable`;
      activeBadge.dataset.color = color;
      // Block-Editor-Select synchronisieren
      const sel = document.querySelector(`select[data-block-id="${activeBadge.dataset.blockId}"]`);
      if (sel) sel.value = color;

      await fetch(url, { method: 'POST', body });
      hidePicker();
    });
  });

  // Picker schließen bei Klick außerhalb
  document.addEventListener('mousedown', e => {
    if (picker && !picker.contains(e.target) && e.target !== activeBadge) {
      hidePicker();
    }
  });

  // ── Badge löschen ────────────────────────────────────────────────────────
  async function deleteBadge(btn) {
    const url = btn.dataset.deleteUrl;
    if (!url) return;
    const wrap = btn.closest('.badge-wrap');
    wrap.style.opacity = '.4';
    const body = new FormData();
    body.append('_method', 'DELETE');
    body.append('_token', csrfToken);
    try {
      const res = await fetch(url, { method: 'POST', body });
      if (res.ok) {
        wrap.remove();
      } else {
        wrap.style.opacity = '';
      }
    } catch {
      wrap.style.opacity = '';
    }
  }

  document.querySelectorAll('.badge-delete-btn').forEach(btn => {
    btn.addEventListener('click', () => deleteBadge(btn));
  });

  // ── Badge Drag & Drop ────────────────────────────────────────────────────
  const reorderUrl = '{{ route('admin.pages.blocks.reorder', [$page, $entry]) }}';

  function initDraggable(wrap) {
    wrap.setAttribute('draggable', 'false'); // default off
    const handle = wrap.querySelector('.badge-drag-handle');

    // Drag nur erlauben wenn Handle gedrückt wird
    handle?.addEventListener('mousedown', () => wrap.setAttribute('draggable', 'true'));
    handle?.addEventListener('mouseup',   () => wrap.setAttribute('draggable', 'false'));
    wrap.addEventListener('dragend',      () => wrap.setAttribute('draggable', 'false'));

    wrap.addEventListener('dragstart', e => {
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/plain', ''); // Firefox requires this
      wrap.classList.add('badge-wrap--dragging');
      window._dragBadge = wrap;
    });

    wrap.addEventListener('dragend', () => {
      wrap.classList.remove('badge-wrap--dragging');
      document.querySelectorAll('.badge-wrap--over').forEach(el => el.classList.remove('badge-wrap--over'));
      window._dragBadge = null;
      saveBadgeOrder();
    });

    wrap.addEventListener('dragover', e => {
      e.preventDefault();
      e.dataTransfer.dropEffect = 'move';
      const dragging = window._dragBadge;
      if (!dragging || dragging === wrap) return;
      // Einfügeposition bestimmen: vor oder nach dem Ziel
      const rect   = wrap.getBoundingClientRect();
      const midX   = rect.left + rect.width / 2;
      const before = e.clientX < midX;
      wrap.classList.add('badge-wrap--over');
      if (before) {
        badgesWrap.insertBefore(dragging, wrap);
      } else {
        wrap.after(dragging);
      }
    });

    wrap.addEventListener('dragleave', () => {
      wrap.classList.remove('badge-wrap--over');
    });
  }

  async function saveBadgeOrder() {
    const ids = [...badgesWrap.querySelectorAll('.badge-wrap')]
      .map(w => w.querySelector('[data-block-id]')?.dataset.blockId)
      .filter(Boolean)
      .map(Number);
    if (!ids.length) return;
    const body = new FormData();
    body.append('_token', csrfToken);
    ids.forEach(id => body.append('ids[]', id));
    await fetch(reorderUrl, { method: 'POST', body });
  }

  // Bestehende Wrappers initialisieren
  document.querySelectorAll('#preview-badges .badge-wrap').forEach(initDraggable);

  // ── Badge hinzufügen (+) ──────────────────────────────────────────────────
  const addBtn      = document.getElementById('badge-add-btn');
  const newForm     = document.getElementById('badge-new-form');
  const newInput    = document.getElementById('badge-new-input');
  const newSave     = document.getElementById('badge-new-save');
  const newCancel   = document.getElementById('badge-new-cancel');
  const badgesWrap  = document.getElementById('preview-badges');
  let   newColor    = 'green';

  // Farb-Buttons im Neu-Formular
  newForm?.querySelectorAll('[data-color]').forEach(btn => {
    if (btn.dataset.color === newColor) btn.classList.add('is-active');
    btn.addEventListener('click', () => {
      newColor = btn.dataset.color;
      newForm.querySelectorAll('[data-color]').forEach(b => b.classList.toggle('is-active', b === btn));
    });
  });

  addBtn?.addEventListener('click', () => {
    newForm.style.display = 'flex';
    newInput.value = '';
    newInput.focus();
    addBtn.style.display = 'none';
  });

  function hideNewForm() {
    newForm.style.display = 'none';
    addBtn.style.display  = '';
  }

  newCancel?.addEventListener('click', hideNewForm);

  newSave?.addEventListener('click', async () => {
    const text = newInput.value.trim();
    if (!text) { newInput.focus(); return; }

    const storeUrl = '{{ route('admin.pages.blocks.store', [$page, $entry]) }}';
    const body = new FormData();
    body.append('_token', csrfToken);
    body.append('type', 'badge');
    body.append('content', text);
    body.append('color', newColor);

    try {
      const res  = await fetch(storeUrl, { method: 'POST', body, headers: { 'Accept': 'application/json' } });
      const data = await res.json();
      if (res.ok && data.id) {
        // Badge-Span erstellen
        const span = document.createElement('span');
        span.className        = `badge badge--${newColor} preview-editable`;
        span.contentEditable  = 'true';
        span.draggable        = false;
        span.dataset.type     = 'block';
        span.dataset.blockId  = data.id;
        span.dataset.color    = newColor;
        span.dataset.url      = data.url;
        span.textContent      = text;

        span.addEventListener('focus', () => showPicker(span));
        span.addEventListener('blur',  () => setTimeout(hidePicker, 150));
        span.addEventListener('keydown', e => {
          if (e.key === 'Enter') { e.preventDefault(); span.blur(); }
        });
        span.addEventListener('blur', async () => {
          const body2 = new FormData();
          body2.append('_method', 'PUT');
          body2.append('_token', csrfToken);
          body2.append('content', span.innerText.trim());
          body2.append('color', span.dataset.color);
          const r = await fetch(span.dataset.url, { method: 'POST', body: body2 });
          if (r.ok) {
            span.classList.add('preview-editable--saved');
            setTimeout(() => span.classList.remove('preview-editable--saved'), 1200);
          }
        });

        // Delete-Button erstellen
        const delBtn = document.createElement('button');
        delBtn.type      = 'button';
        delBtn.className = 'badge-delete-btn';
        delBtn.title     = 'Badge löschen';
        delBtn.textContent = '×';
        delBtn.dataset.deleteUrl = data.deleteUrl ?? '';
        delBtn.addEventListener('click', () => deleteBadge(delBtn));

        // Drag-Handle
        const handle = document.createElement('span');
        handle.className = 'badge-drag-handle';
        handle.title     = 'Verschieben';
        handle.textContent = '⠿';

        // Wrapper
        const wrap = document.createElement('span');
        wrap.className = 'badge-wrap';
        wrap.appendChild(handle);
        wrap.appendChild(span);
        wrap.appendChild(delBtn);

        initDraggable(wrap);
        badgesWrap.insertBefore(wrap, addBtn);
        hideNewForm();
      }
    } catch (err) {
      console.error('Badge speichern fehlgeschlagen', err);
    }
  });

  // Enter im Input = Speichern
  newInput?.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); newSave.click(); }
    if (e.key === 'Escape') hideNewForm();
  });

  document.querySelectorAll('.preview-editable').forEach(el => {
    const isBadge = el.dataset.type === 'block' && el.classList.contains('badge');

    // Badge: Picker bei Focus zeigen + auto-save on blur
    if (isBadge) {
      el.addEventListener('focus', () => showPicker(el));
      el.addEventListener('blur',  () => setTimeout(hidePicker, 150));
    }

    // Enter bei einzeiligen Elementen → blur statt Zeilenumbruch
    el.addEventListener('keydown', e => {
      if (e.key === 'Enter' && !el.classList.contains('preview-editable--multiline')) {
        e.preventDefault();
        el.blur();
      }
    });

    // Blur-save nur noch für Badges — alles andere läuft über Speichern-Button
    if (!isBadge) return;

    el.addEventListener('blur', async () => {
      const text = el.innerText.replace(/\n{3,}/g, '\n\n').trim();
      const url  = el.dataset.url;

      const body = new FormData();
      body.append('_method', 'PUT');
      body.append('_token', csrfToken);
      body.append('content', text);
      if (el.dataset.color) body.append('color', el.dataset.color);

      try {
        const res = await fetch(url, { method: 'POST', body });
        if (res.ok) {
          el.classList.add('preview-editable--saved');
          setTimeout(() => el.classList.remove('preview-editable--saved'), 1200);
        } else {
          el.classList.add('preview-editable--error');
          setTimeout(() => el.classList.remove('preview-editable--error'), 2000);
        }
      } catch {
        el.classList.add('preview-editable--error');
        setTimeout(() => el.classList.remove('preview-editable--error'), 2000);
      }
    });
  });

  // ── Cards: Speichern-Button ──────────────────────────────────────────────────
  const cardSaveBtn = document.getElementById('card-save');
  if (cardSaveBtn) {
    cardSaveBtn.addEventListener('click', async () => {
      const title      = document.getElementById('card-title')?.innerText.trim() ?? '';
      const titleEn    = document.getElementById('card-title-en')?.innerText.trim() ?? '';
      const desc       = document.getElementById('card-desc')?.innerText.replace(/\n{3,}/g, '\n\n').trim() ?? '';
      const descEn     = document.getElementById('card-desc-en')?.innerText.replace(/\n{3,}/g, '\n\n').trim() ?? '';
      const hlTitle    = document.getElementById('card-hl-title')?.innerText.trim() ?? '';
      const hlBody     = document.getElementById('card-hl-body')?.innerText.trim() ?? '';
      const hlTitleEn  = document.getElementById('card-hl-title-en')?.innerText.trim() ?? '';
      const hlBodyEn   = document.getElementById('card-hl-body-en')?.innerText.trim() ?? '';
      const hl         = hlTitle ? hlTitle + '\n' + hlBody : hlBody;
      const hlEn       = hlTitleEn ? hlTitleEn + '\n' + hlBodyEn : hlBodyEn;

      cardSaveBtn.disabled = true;

      async function saveBlock(hasBlock, updateUrl, storeUrl, content, type = 'text') {
        const body = new FormData();
        body.append('_token', csrfToken);
        body.append('content', content);
        if (hasBlock && updateUrl) {
          body.append('_method', 'PUT');
          return fetch(updateUrl, { method: 'POST', body });
        }
        body.append('type', type);
        return fetch(storeUrl, { method: 'POST', body });
      }

      try {
        const titleBody = new FormData();
        titleBody.append('_method', 'PUT');
        titleBody.append('_token', csrfToken);
        titleBody.append('title', title);
        titleBody.append('title_en', titleEn);
        const r1 = await fetch(cardSaveBtn.dataset.entryUrl, { method: 'POST', body: titleBody });

        const r2 = await saveBlock(
          cardSaveBtn.dataset.descHasBlock === '1',
          cardSaveBtn.dataset.descUpdateUrl,
          cardSaveBtn.dataset.descStoreUrl,
          desc
        );

        const r3 = await saveBlock(
          cardSaveBtn.dataset.hlHasBlock === '1',
          cardSaveBtn.dataset.hlUpdateUrl,
          cardSaveBtn.dataset.hlStoreUrl,
          hl
        );

        const r4 = await saveBlock(
          cardSaveBtn.dataset.descEnHasBlock === '1',
          cardSaveBtn.dataset.descEnUpdateUrl,
          cardSaveBtn.dataset.descEnStoreUrl,
          descEn, 'text_en'
        );

        const r5 = await saveBlock(
          cardSaveBtn.dataset.hlEnHasBlock === '1',
          cardSaveBtn.dataset.hlEnUpdateUrl,
          cardSaveBtn.dataset.hlEnStoreUrl,
          hlEn, 'text_en'
        );

        const allOk = r1.ok && r2.ok && r3.ok && r4.ok && r5.ok;
        if (allOk) {
          cardSaveBtn.textContent = 'Gespeichert ✓';
          cardSaveBtn.classList.add('btn-save--saved');
          const needsReload = cardSaveBtn.dataset.descHasBlock === '0' || cardSaveBtn.dataset.hlHasBlock === '0'
            || cardSaveBtn.dataset.descEnHasBlock === '0' || cardSaveBtn.dataset.hlEnHasBlock === '0';
          setTimeout(() => {
            if (needsReload) {
              location.reload();
            } else {
              cardSaveBtn.textContent = 'Speichern';
              cardSaveBtn.classList.remove('btn-save--saved');
              cardSaveBtn.disabled = false;
            }
          }, 1200);
        } else {
          cardSaveBtn.textContent = 'Fehler – erneut versuchen';
          cardSaveBtn.disabled = false;
          setTimeout(() => { cardSaveBtn.textContent = 'Speichern'; }, 2000);
        }
      } catch {
        cardSaveBtn.textContent = 'Fehler – erneut versuchen';
        cardSaveBtn.disabled = false;
        setTimeout(() => { cardSaveBtn.textContent = 'Speichern'; }, 2000);
      }
    });
  }

  // ── Hero-Feature Karte: Speichern-Button ─────────────────────────────────────
  const hfCardSaveBtn = document.getElementById('hf-card-save');
  if (hfCardSaveBtn) {
    hfCardSaveBtn.addEventListener('click', async () => {
      const title      = document.getElementById('hf-card-title')?.innerText.trim() ?? '';
      const titleEn    = document.getElementById('hf-card-title-en')?.innerText.trim() ?? '';
      const desc       = document.getElementById('hf-card-desc')?.innerText.replace(/\n{3,}/g, '\n\n').trim() ?? '';
      const descEn     = document.getElementById('hf-card-desc-en')?.innerText.replace(/\n{3,}/g, '\n\n').trim() ?? '';
      const hlTitle    = document.getElementById('hf-card-hl-title')?.innerText.trim() ?? '';
      const hlBody     = document.getElementById('hf-card-hl-body')?.innerText.trim() ?? '';
      const hlTitleEn  = document.getElementById('hf-card-hl-title-en')?.innerText.trim() ?? '';
      const hlBodyEn   = document.getElementById('hf-card-hl-body-en')?.innerText.trim() ?? '';
      const hl         = hlTitle ? hlTitle + '\n' + hlBody : hlBody;
      const hlEn       = hlTitleEn ? hlTitleEn + '\n' + hlBodyEn : hlBodyEn;

      hfCardSaveBtn.disabled = true;

      async function saveHfBlock(hasBlock, updateUrl, storeUrl, content, type = 'text') {
        const body = new FormData();
        body.append('_token', csrfToken);
        body.append('content', content);
        if (hasBlock && updateUrl) {
          body.append('_method', 'PUT');
          return fetch(updateUrl, { method: 'POST', body });
        }
        body.append('type', type);
        return fetch(storeUrl, { method: 'POST', body });
      }

      try {
        const titleBody = new FormData();
        titleBody.append('_method', 'PUT');
        titleBody.append('_token', csrfToken);
        titleBody.append('title', title);
        titleBody.append('title_en', titleEn);
        const r1 = await fetch(hfCardSaveBtn.dataset.entryUrl, { method: 'POST', body: titleBody });

        const r2 = await saveHfBlock(
          hfCardSaveBtn.dataset.descHasBlock === '1',
          hfCardSaveBtn.dataset.descUpdateUrl,
          hfCardSaveBtn.dataset.descStoreUrl,
          desc
        );

        const r3 = await saveHfBlock(
          hfCardSaveBtn.dataset.hlHasBlock === '1',
          hfCardSaveBtn.dataset.hlUpdateUrl,
          hfCardSaveBtn.dataset.hlStoreUrl,
          hl
        );

        const r4 = await saveHfBlock(
          hfCardSaveBtn.dataset.descEnHasBlock === '1',
          hfCardSaveBtn.dataset.descEnUpdateUrl,
          hfCardSaveBtn.dataset.descEnStoreUrl,
          descEn, 'text_en'
        );

        const r5 = await saveHfBlock(
          hfCardSaveBtn.dataset.hlEnHasBlock === '1',
          hfCardSaveBtn.dataset.hlEnUpdateUrl,
          hfCardSaveBtn.dataset.hlEnStoreUrl,
          hlEn, 'text_en'
        );

        const allOk = r1.ok && r2.ok && r3.ok && r4.ok && r5.ok;
        if (allOk) {
          hfCardSaveBtn.textContent = 'Gespeichert ✓';
          hfCardSaveBtn.classList.add('btn-save--saved');
          const needsReload = hfCardSaveBtn.dataset.descHasBlock === '0' || hfCardSaveBtn.dataset.hlHasBlock === '0'
            || hfCardSaveBtn.dataset.descEnHasBlock === '0' || hfCardSaveBtn.dataset.hlEnHasBlock === '0';
          setTimeout(() => {
            if (needsReload) {
              location.reload();
            } else {
              hfCardSaveBtn.textContent = 'Speichern';
              hfCardSaveBtn.classList.remove('btn-save--saved');
              hfCardSaveBtn.disabled = false;
            }
          }, 1200);
        } else {
          hfCardSaveBtn.textContent = 'Fehler – erneut versuchen';
          hfCardSaveBtn.disabled = false;
          setTimeout(() => { hfCardSaveBtn.textContent = 'Speichern'; }, 2000);
        }
      } catch {
        hfCardSaveBtn.textContent = 'Fehler – erneut versuchen';
        hfCardSaveBtn.disabled = false;
        setTimeout(() => { hfCardSaveBtn.textContent = 'Speichern'; }, 2000);
      }
    });
  }

  // ── Feature: Bildseite per Button wechseln ──────────────────────────────────
  const imgSideToggle = document.getElementById('img-side-toggle');
  if (imgSideToggle) {
    imgSideToggle.addEventListener('click', async () => {
      const card   = document.getElementById('place-card');
      const newPos = imgSideToggle.dataset.position === 'right' ? 'left' : 'right';
      imgSideToggle.dataset.position = newPos;
      card?.classList.toggle('feature--rev', newPos === 'right');
      imgSideToggle.querySelector('.img-side-toggle__label').textContent = 'Bild ' + (newPos === 'right' ? 'links' : 'rechts');

      const body = new FormData();
      body.append('_method', 'PUT');
      body.append('_token', csrfToken);
      body.append('title', document.getElementById('place-title')?.innerText.trim() ?? '');
      body.append('image_position', newPos);
      const res = await fetch(imgSideToggle.dataset.entryUrl, { method: 'POST', body });
      if (res.ok) {
        imgSideToggle.classList.add('btn-save--saved');
        setTimeout(() => imgSideToggle.classList.remove('btn-save--saved'), 1000);
      }
    });
  }

  // ── Place/Feature: Speichern (Titel, Beschreibung 1+2, Bildseite) ─────────────
  const placeSaveBtn2 = document.getElementById('place-save');
  if (placeSaveBtn2) {
    placeSaveBtn2.addEventListener('click', async () => {
      const title         = document.getElementById('place-title')?.innerText.trim() ?? '';
      const titleEn       = document.getElementById('place-title-en')?.innerText.trim() ?? '';
      const desc          = document.getElementById('place-desc')?.innerText.trim() ?? '';
      const descEn        = document.getElementById('place-desc-en')?.innerText.trim() ?? '';
      const desc2         = document.getElementById('place-desc2')?.innerText.trim() ?? '';
      const desc2En       = document.getElementById('place-desc2-en')?.innerText.trim() ?? '';
      const imagePosition = document.getElementById('place-img-position')?.value ?? null;

      placeSaveBtn2.disabled = true;

      async function saveTextBlock(hasBlock, updateUrl, storeUrl, content, type = 'text') {
        const body = new FormData();
        body.append('_token', csrfToken);
        body.append('content', content);
        if (hasBlock && updateUrl) {
          body.append('_method', 'PUT');
          return fetch(updateUrl, { method: 'POST', body });
        }
        body.append('type', type);
        return fetch(storeUrl, { method: 'POST', body });
      }

      try {
        const titleBody = new FormData();
        titleBody.append('_method', 'PUT');
        titleBody.append('_token', csrfToken);
        titleBody.append('title', title);
        titleBody.append('title_en', titleEn);
        if (imagePosition) titleBody.append('image_position', imagePosition);
        const r1 = await fetch(placeSaveBtn2.dataset.entryUrl, { method: 'POST', body: titleBody });

        const r2 = await saveTextBlock(
          placeSaveBtn2.dataset.descHasBlock === '1',
          placeSaveBtn2.dataset.descUpdateUrl,
          placeSaveBtn2.dataset.descStoreUrl,
          desc
        );

        const results = [r1, r2];

        if (desc2 || placeSaveBtn2.dataset.desc2HasBlock === '1') {
          const r3 = await saveTextBlock(
            placeSaveBtn2.dataset.desc2HasBlock === '1',
            placeSaveBtn2.dataset.desc2UpdateUrl,
            placeSaveBtn2.dataset.desc2StoreUrl,
            desc2
          );
          results.push(r3);
        }

        const r4 = await saveTextBlock(
          placeSaveBtn2.dataset.descEnHasBlock === '1',
          placeSaveBtn2.dataset.descEnUpdateUrl,
          placeSaveBtn2.dataset.descEnStoreUrl,
          descEn, 'text_en'
        );
        results.push(r4);

        if (desc2En || placeSaveBtn2.dataset.desc2EnHasBlock === '1') {
          const r5 = await saveTextBlock(
            placeSaveBtn2.dataset.desc2EnHasBlock === '1',
            placeSaveBtn2.dataset.desc2EnUpdateUrl,
            placeSaveBtn2.dataset.desc2EnStoreUrl,
            desc2En, 'text_en'
          );
          results.push(r5);
        }

        const allOk = results.every(r => r.ok);
        if (allOk) {
          placeSaveBtn2.textContent = 'Gespeichert ✓';
          placeSaveBtn2.classList.add('btn-save--saved');
          const needsReload = placeSaveBtn2.dataset.descHasBlock === '0' || placeSaveBtn2.dataset.descEnHasBlock === '0';
          setTimeout(() => {
            if (needsReload) {
              location.reload();
            } else {
              placeSaveBtn2.textContent = 'Speichern';
              placeSaveBtn2.classList.remove('btn-save--saved');
              placeSaveBtn2.disabled = false;
            }
          }, 1200);
        } else {
          placeSaveBtn2.textContent = 'Fehler – erneut versuchen';
          placeSaveBtn2.disabled = false;
          setTimeout(() => { placeSaveBtn2.textContent = 'Speichern'; }, 2000);
        }
      } catch {
        placeSaveBtn2.textContent = 'Fehler – erneut versuchen';
        placeSaveBtn2.disabled = false;
        setTimeout(() => { placeSaveBtn2.textContent = 'Speichern'; }, 2000);
      }
    });
  }

  // ── Body-Block Drag (Info-Section ↔ Text-Section) ────────────────────────────
  (function initBodyBlocks() {
    const container = document.getElementById('body-blocks');
    if (!container) return;
    let dragBlock = null;

    container.querySelectorAll('.body-block').forEach(block => {
      const handle = block.querySelector('.body-block__drag-handle');
      handle?.addEventListener('mousedown', () => block.setAttribute('draggable', 'true'));
      handle?.addEventListener('mouseup',   () => block.setAttribute('draggable', 'false'));

      block.addEventListener('dragstart', e => {
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', '');
        block.classList.add('body-block--dragging');
        dragBlock = block;
      });

      block.addEventListener('dragend', () => {
        block.classList.remove('body-block--dragging');
        block.setAttribute('draggable', 'false');
        container.querySelectorAll('.body-block--over').forEach(el => el.classList.remove('body-block--over'));
        dragBlock = null;
        saveBodyBlockOrder();
      });

      block.addEventListener('dragover', e => {
        e.preventDefault();
        if (!dragBlock || dragBlock === block) return;
        const rect   = block.getBoundingClientRect();
        const before = e.clientY < rect.top + rect.height / 2;
        block.classList.add('body-block--over');
        if (before) container.insertBefore(dragBlock, block);
        else block.after(dragBlock);
      });

      block.addEventListener('dragleave', () => block.classList.remove('body-block--over'));
    });

    async function saveBodyBlockOrder() {
      const reorderUrl = container.dataset.reorderUrl;
      if (!reorderUrl) return;
      const ids = [];
      container.querySelectorAll('.body-block').forEach(block => {
        if (block.dataset.type === 'info-section') {
          block.querySelectorAll('.info-chip[data-block-id]').forEach(chip => {
            ids.push(Number(chip.dataset.blockId));
          });
        } else if (block.dataset.type === 'text-section') {
          const textIds = block.dataset.blockIds?.split(',').map(Number).filter(Boolean) ?? [];
          ids.push(...textIds);
        }
      });
      if (!ids.length) return;
      const body = new FormData();
      body.append('_token', csrfToken);
      ids.forEach(id => body.append('ids[]', id));
      await fetch(reorderUrl, { method: 'POST', body });
    }
  })();

  // ── Info-Chips: Drag, Icon, Text, Add, Delete ─────────────────────────────────
  (function initInfoChips() {
    const infoList = document.getElementById('info-items-list');
    if (!infoList) return;

    const infoStoreUrl   = infoList.dataset.storeUrl;
    const infoReorderUrl = infoList.dataset.reorderUrl;
    const defaultIcon    = infoList.dataset.defaultIcon ?? 'info';
    let   dragChip       = null;

    function initChip(chip) {
      chip.setAttribute('draggable', 'false');
      const handle = chip.querySelector('.info-chip__drag-handle');

      handle?.addEventListener('mousedown', () => chip.setAttribute('draggable', 'true'));
      handle?.addEventListener('mouseup',   () => chip.setAttribute('draggable', 'false'));
      chip.addEventListener('dragend',      () => chip.setAttribute('draggable', 'false'));

      chip.addEventListener('dragstart', e => {
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', '');
        chip.classList.add('info-chip--dragging');
        dragChip = chip;
      });

      chip.addEventListener('dragend', () => {
        chip.classList.remove('info-chip--dragging');
        infoList.querySelectorAll('.info-chip--over').forEach(el => el.classList.remove('info-chip--over'));
        dragChip = null;
        saveChipOrder();
      });

      chip.addEventListener('dragover', e => {
        e.preventDefault();
        if (!dragChip || dragChip === chip) return;
        const rect   = chip.getBoundingClientRect();
        const before = e.clientX < rect.left + rect.width / 2;
        chip.classList.add('info-chip--over');
        if (before) infoList.insertBefore(dragChip, chip);
        else chip.after(dragChip);
      });

      chip.addEventListener('dragleave', () => chip.classList.remove('info-chip--over'));

      // Icon-Select: AJAX + live Vorschau
      const iconSelect  = chip.querySelector('.info-chip__icon-select');
      const iconPreview = chip.querySelector('.info-chip__icon-preview');
      iconSelect?.addEventListener('change', async () => {
        const icon    = iconSelect.value;
        const content = chip.querySelector('.info-chip__text')?.innerText.trim() ?? '';
        if (iconPreview) iconPreview.textContent = icon;
        const body = new FormData();
        body.append('_method', 'PUT');
        body.append('_token', csrfToken);
        body.append('icon', icon);
        body.append('content', content);
        await fetch(iconSelect.dataset.updateUrl, { method: 'POST', body, headers: { 'Accept': 'application/json' } });
      });

      // Text: AJAX blur-save
      const textEl = chip.querySelector('.info-chip__text');
      textEl?.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); textEl.blur(); }
      });
      textEl?.addEventListener('blur', async () => {
        const content = textEl.innerText.trim();
        const icon    = chip.querySelector('.info-chip__icon-select')?.value ?? '';
        const body = new FormData();
        body.append('_method', 'PUT');
        body.append('_token', csrfToken);
        body.append('content', content);
        body.append('icon', icon);
        const res = await fetch(textEl.dataset.updateUrl, { method: 'POST', body, headers: { 'Accept': 'application/json' } });
        if (res.ok) {
          textEl.classList.add('preview-editable--saved');
          setTimeout(() => textEl.classList.remove('preview-editable--saved'), 1200);
        }
      });

      // Delete
      const delBtn = chip.querySelector('.info-chip__delete');
      delBtn?.addEventListener('click', async () => {
        const body = new FormData();
        body.append('_method', 'DELETE');
        body.append('_token', csrfToken);
        chip.style.opacity = '.4';
        const res = await fetch(delBtn.dataset.deleteUrl, { method: 'POST', body, headers: { 'Accept': 'application/json' } });
        if (res.ok) chip.remove();
        else chip.style.opacity = '';
      });
    }

    async function saveChipOrder() {
      const ids = [...infoList.querySelectorAll('.info-chip[data-block-id]')]
        .map(c => Number(c.dataset.blockId))
        .filter(Boolean);
      if (!ids.length) return;
      const body = new FormData();
      body.append('_token', csrfToken);
      ids.forEach(id => body.append('ids[]', id));
      await fetch(infoReorderUrl, { method: 'POST', body });
    }

    // Bestehende Chips initialisieren
    infoList.querySelectorAll('.info-chip').forEach(initChip);

    // Chip hinzufügen
    document.getElementById('info-item-add-btn')?.addEventListener('click', async () => {
      const body = new FormData();
      body.append('_token', csrfToken);
      body.append('type', 'info');
      body.append('content', '');
      body.append('icon', defaultIcon);

      const res  = await fetch(infoStoreUrl, { method: 'POST', body, headers: { 'Accept': 'application/json' } });
      const data = await res.json();
      if (!res.ok || !data.id) return;

      const existingSelect = infoList.querySelector('.info-chip__icon-select');
      const optionsHtml = existingSelect
        ? existingSelect.innerHTML
        : `<option value="${defaultIcon}">${defaultIcon}</option>`;

      const addBtn = document.getElementById('info-item-add-btn');
      const chip   = document.createElement('div');
      chip.className       = 'info-chip';
      chip.dataset.blockId = data.id;
      chip.innerHTML = `
        <span class="info-chip__drag-handle" title="Verschieben">⠿</span>
        <span class="info-chip__icon-wrapper" title="Icon ändern">
          <span class="material-symbols-rounded info-chip__icon-preview">${defaultIcon}</span>
          <select class="info-chip__icon-select"
                  data-block-id="${data.id}"
                  data-update-url="${data.url}">${optionsHtml}</select>
        </span>
        <span class="info-chip__text"
              contenteditable="true"
              data-block-id="${data.id}"
              data-update-url="${data.url}"></span>
        <button type="button" class="info-chip__delete" title="Löschen"
                data-delete-url="${data.deleteUrl ?? ''}">×</button>
      `;
      chip.querySelector('.info-chip__icon-select').value = defaultIcon;
      infoList.insertBefore(chip, addBtn);
      initChip(chip);
      chip.querySelector('.info-chip__text')?.focus();
    });
  })();

  // ── Hero-Feature: Hero-Eintrag speichern ────────────────────────────────────
  const heroSaveBtn = document.getElementById('hero-save');
  if (heroSaveBtn) {
    heroSaveBtn.addEventListener('click', async () => {
      const title   = document.getElementById('hero-title')?.innerText.trim() ?? '';
      const titleEn = document.getElementById('hero-title-en')?.innerText.trim() ?? '';
      const text    = document.getElementById('hero-text')?.innerText.trim() ?? '';
      const textEn  = document.getElementById('hero-text-en')?.innerText.trim() ?? '';

      const factParts = [];
      document.querySelectorAll('#hero-facts-grid .hero-fact:not(.hero-fact--add)').forEach(el => {
        const label = el.querySelector('.fact__label')?.innerText.trim();
        const value = el.querySelector('.fact__value')?.innerText.trim();
        if (label && value) factParts.push(`${label}: ${value}`);
      });
      const newLabel = document.getElementById('hero-fact-label-new')?.innerText.trim();
      const newValue = document.getElementById('hero-fact-value-new')?.innerText.trim();
      if (newLabel && newValue) factParts.push(`${newLabel}: ${newValue}`);
      const facts = factParts.join(' · ');

      heroSaveBtn.disabled = true;

      async function saveBlock(hasBlock, updateUrl, storeUrl, content, type = 'text') {
        const body = new FormData();
        body.append('_token', csrfToken);
        body.append('content', content);
        if (hasBlock && updateUrl) {
          body.append('_method', 'PUT');
          return fetch(updateUrl, { method: 'POST', body });
        }
        body.append('type', type);
        return fetch(storeUrl, { method: 'POST', body });
      }

      try {
        const titleBody = new FormData();
        titleBody.append('_method', 'PUT');
        titleBody.append('_token', csrfToken);
        titleBody.append('title', title);
        titleBody.append('title_en', titleEn);
        const r1 = await fetch(heroSaveBtn.dataset.entryUrl, { method: 'POST', body: titleBody });

        const r2 = await saveBlock(
          heroSaveBtn.dataset.textHasBlock === '1',
          heroSaveBtn.dataset.textUpdateUrl,
          heroSaveBtn.dataset.textStoreUrl,
          text
        );

        const r3 = await saveBlock(
          heroSaveBtn.dataset.factsHasBlock === '1',
          heroSaveBtn.dataset.factsUpdateUrl,
          heroSaveBtn.dataset.factsStoreUrl,
          facts
        );

        const r4 = await saveBlock(
          heroSaveBtn.dataset.textEnHasBlock === '1',
          heroSaveBtn.dataset.textEnUpdateUrl,
          heroSaveBtn.dataset.textEnStoreUrl,
          textEn, 'text_en'
        );

        const allOk = r1.ok && r2.ok && r3.ok && r4.ok;
        if (allOk) {
          heroSaveBtn.textContent = 'Gespeichert ✓';
          heroSaveBtn.classList.add('btn-save--saved');
          const newFactAdded = !!(newLabel && newValue);
          const needsReload = heroSaveBtn.dataset.textHasBlock === '0' || heroSaveBtn.dataset.factsHasBlock === '0'
            || heroSaveBtn.dataset.textEnHasBlock === '0' || newFactAdded;
          setTimeout(() => {
            if (needsReload) {
              location.reload();
            } else {
              heroSaveBtn.textContent = 'Speichern';
              heroSaveBtn.classList.remove('btn-save--saved');
              heroSaveBtn.disabled = false;
            }
          }, 1200);
        } else {
          heroSaveBtn.textContent = 'Fehler – erneut versuchen';
          heroSaveBtn.disabled = false;
          setTimeout(() => { heroSaveBtn.textContent = 'Speichern'; }, 2000);
        }
      } catch {
        heroSaveBtn.textContent = 'Fehler – erneut versuchen';
        heroSaveBtn.disabled = false;
        setTimeout(() => { heroSaveBtn.textContent = 'Speichern'; }, 2000);
      }
    });
  }

  // ── Lang-Toggle (DE/EN) — globaler Toggle im page-header ───────────────────
  const globalLangToggle = document.getElementById('global-lang-toggle');
  if (globalLangToggle) {
    globalLangToggle.querySelectorAll('.lang-toggle-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const lang = btn.dataset.lang;
        globalLangToggle.querySelectorAll('.lang-toggle-btn').forEach(b => b.classList.toggle('lang-toggle-btn--active', b === btn));
        document.querySelectorAll('[data-lang]:not(.lang-toggle-btn)').forEach(el => {
          el.style.display = el.dataset.lang === lang ? '' : 'none';
        });
      });
    });
  }

  // ── Route: Diff-Select live einfärben ───────────────────────────────────────
  const routeDiffSelect = document.getElementById('route-diff');
  const diffClassMap = { leicht: 'diff--easy', moderat: 'diff--medium', schwer: 'diff--hard' };
  routeDiffSelect?.addEventListener('change', () => {
    routeDiffSelect.className = `route-diff-select route-diff-select--${diffClassMap[routeDiffSelect.value] ?? 'diff--easy'}`;
  });

  // ── Route: Enter-Taste auf einzeiligen contenteditable-Elementen ────────────
  ['route-title', 'route-length-val', 'route-length-label', 'route-duration-val', 'route-duration-label'].forEach(id => {
    document.getElementById(id)?.addEventListener('keydown', e => {
      if (e.key === 'Enter') { e.preventDefault(); e.target.blur(); }
    });
  });

  // ── Route: Alles auf einmal speichern ───────────────────────────────────────
  const routeStatsBtn = document.getElementById('route-stats-save');
  if (routeStatsBtn) {
    routeStatsBtn.addEventListener('click', async () => {
      const title         = document.getElementById('route-title')?.innerText.trim() ?? '';
      const titleEn       = document.getElementById('route-title-en')?.innerText.trim() ?? '';
      const desc          = document.getElementById('route-desc')?.innerText.trim() ?? '';
      const descEn        = document.getElementById('route-desc-en')?.innerText.trim() ?? '';
      const lengthVal     = document.getElementById('route-length-val')?.innerText.trim() ?? '';
      const lengthLabel   = document.getElementById('route-length-label')?.innerText.trim() || 'Gesamtlänge';
      const diff          = document.getElementById('route-diff')?.value ?? 'leicht';
      const durationVal   = document.getElementById('route-duration-val')?.innerText.trim() ?? '';
      const durationLabel = document.getElementById('route-duration-label')?.innerText.trim() || 'Dauer';

      const parts = [];
      if (lengthVal)   parts.push(`${lengthLabel}: ${lengthVal}`);
      parts.push(`Schwierigkeit: ${diff}`);
      if (durationVal) parts.push(`${durationLabel}: ${durationVal}`);
      const statsStr = parts.join(' · ');

      routeStatsBtn.disabled = true;

      async function saveBlock(hasBlock, updateUrl, storeUrl, content, type = 'text') {
        const body = new FormData();
        body.append('_token', csrfToken);
        body.append('content', content);
        if (hasBlock && updateUrl) {
          body.append('_method', 'PUT');
          return fetch(updateUrl, { method: 'POST', body });
        } else {
          body.append('type', type);
          return fetch(storeUrl, { method: 'POST', body });
        }
      }

      try {
        // 1. Titel speichern
        const titleBody = new FormData();
        titleBody.append('_method', 'PUT');
        titleBody.append('_token', csrfToken);
        titleBody.append('title', title);
        titleBody.append('title_en', titleEn);
        const r1 = await fetch(routeStatsBtn.dataset.entryUrl, { method: 'POST', body: titleBody });

        // 2. Beschreibungsblock speichern
        const r2 = await saveBlock(
          routeStatsBtn.dataset.descHasBlock === '1',
          routeStatsBtn.dataset.descUpdateUrl,
          routeStatsBtn.dataset.descStoreUrl,
          desc
        );

        // 3. Streckendaten-Block speichern
        const r3 = await saveBlock(
          routeStatsBtn.dataset.statsHasBlock === '1',
          routeStatsBtn.dataset.statsUpdateUrl,
          routeStatsBtn.dataset.statsStoreUrl,
          statsStr
        );

        // 4. Beschreibung EN speichern
        const r4 = await saveBlock(
          routeStatsBtn.dataset.descEnHasBlock === '1',
          routeStatsBtn.dataset.descEnUpdateUrl,
          routeStatsBtn.dataset.descEnStoreUrl,
          descEn, 'text_en'
        );

        const allOk = r1.ok && r2.ok && r3.ok && r4.ok;
        if (allOk) {
          routeStatsBtn.textContent = 'Gespeichert ✓';
          routeStatsBtn.classList.add('btn-save--saved');
          // Seite neu laden wenn neue Blöcke erstellt wurden (damit Update-URLs gesetzt werden)
          const needsReload = routeStatsBtn.dataset.descHasBlock === '0' || routeStatsBtn.dataset.statsHasBlock === '0'
            || routeStatsBtn.dataset.descEnHasBlock === '0';
          setTimeout(() => {
            if (needsReload) {
              location.reload();
            } else {
              routeStatsBtn.textContent = 'Speichern';
              routeStatsBtn.classList.remove('btn-save--saved');
              routeStatsBtn.disabled = false;
            }
          }, 1200);
        } else {
          routeStatsBtn.textContent = 'Fehler – erneut versuchen';
          routeStatsBtn.disabled = false;
          setTimeout(() => { routeStatsBtn.textContent = 'Speichern'; }, 2000);
        }
      } catch {
        routeStatsBtn.textContent = 'Fehler – erneut versuchen';
        routeStatsBtn.disabled = false;
        setTimeout(() => { routeStatsBtn.textContent = 'Speichern'; }, 2000);
      }
    });
  }

});
</script>
@endpush
