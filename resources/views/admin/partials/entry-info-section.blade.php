<div class="body-block" data-type="info-section" id="body-block-info">
  <div class="body-block__header">
    <span class="body-block__drag-handle" title="Abschnitt verschieben">⠿</span>
    <span class="body-block__label">Info-Items</span>
  </div>
  <div class="info-items-chips" id="info-items-list"
       data-store-url="{{ route('admin.pages.blocks.store', [$page, $entry]) }}"
       data-reorder-url="{{ route('admin.pages.blocks.reorder', [$page, $entry]) }}"
       data-default-icon="{{ $defaultIcon }}">
    @foreach ($infoBlocks as $infoBlock)
    <div class="info-chip" data-block-id="{{ $infoBlock->id }}">
      <span class="info-chip__drag-handle" title="Verschieben">⠿</span>
      <span class="info-chip__icon-wrapper" title="Icon ändern">
        <span class="material-symbols-rounded info-chip__icon-preview">{{ $infoBlock->icon ?? $defaultIcon }}</span>
        <select class="info-chip__icon-select"
                data-block-id="{{ $infoBlock->id }}"
                data-update-url="{{ route('admin.pages.blocks.update', [$page, $entry, $infoBlock]) }}">
          @foreach ($icons as $iconName => $iconLabel)
            <option value="{{ $iconName }}" @selected(($infoBlock->icon ?? $defaultIcon) === $iconName)>{{ $iconLabel }}</option>
          @endforeach
        </select>
      </span>
      <span class="info-chip__text"
            contenteditable="true"
            data-block-id="{{ $infoBlock->id }}"
            data-update-url="{{ route('admin.pages.blocks.update', [$page, $entry, $infoBlock]) }}">{{ $infoBlock->content }}</span>
      <button type="button" class="info-chip__delete" title="Löschen"
              data-delete-url="{{ route('admin.pages.blocks.destroy', [$page, $entry, $infoBlock]) }}">×</button>
    </div>
    @endforeach
    <button type="button" class="info-item-add-btn" id="info-item-add-btn" title="Info hinzufügen">
      <span class="material-symbols-rounded">add</span>
    </button>
  </div>
</div>
