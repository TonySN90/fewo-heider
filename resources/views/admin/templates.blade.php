@extends('layouts.admin')

@section('title', 'Templates')

@section('content')
  <div class="page-header">
    <h1>Website-Templates</h1>
  </div>

  @if ($templates->isEmpty())
    <div class="card">
      <p style="color:#aaa;text-align:center">Noch keine Templates vorhanden.</p>
    </div>
  @else
    <div class="seasons-tabs">
      <div class="seasons-tabs__bar" role="tablist">
        @foreach ($templates as $template)
          <button
            class="seasons-tabs__tab {{ $loop->first ? 'is-active' : '' }}"
            role="tab"
            data-tab="tpl-{{ $template->id }}"
            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
          >
            {{ $template->name }}
            @if ($template->is_active)
              <span class="seasons-tabs__dot" title="Aktives Template"></span>
            @endif
          </button>
        @endforeach
      </div>

      @foreach ($templates as $template)
        <div class="seasons-tabs__panel {{ $loop->first ? 'is-active' : '' }}" id="tpl-{{ $template->id }}" role="tabpanel">

          {{-- Panel-Header --}}
          <div class="season-panel__header">
            <div class="season-panel__meta">
              <span class="season-panel__name">{{ $template->name }}</span>
              @if ($template->is_active)
                <span class="season-badge season-badge--green">aktiv</span>
              @endif
            </div>
            <div class="actions">
              @unless ($template->is_active)
                <form method="POST" action="{{ route('admin.templates.activate', $template) }}">
                  @csrf
                  <button type="submit" class="btn btn-activate">Aktiv setzen</button>
                </form>
              @endunless
              <button class="btn btn-edit" onclick="openModal('Template umbenennen', 'rename-tpl-{{ $template->id }}')">
                <span class="material-symbols-rounded">edit</span>
              </button>
            </div>
          </div>

          {{-- Sektionen-Formular --}}
          <form method="POST" action="{{ route('admin.templates.sections', $template) }}">
            @csrf
            @method('PUT')
            <div class="table-card" style="box-shadow:none;margin-bottom:0;border-radius:0">
              <table>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Sektion</th>
                    <th>Sichtbar</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($template->sections as $section)
                    <tr>
                      <td>{{ $section->sort_order }}</td>
                      <td>{{ $sectionLabels[$section->section_key] ?? $section->section_key }}</td>
                      <td>
                        <input type="hidden" name="sections[{{ $section->section_key }}]" value="0" />
                        <label class="toggle">
                          <input
                            type="checkbox"
                            name="sections[{{ $section->section_key }}]"
                            value="1"
                            {{ $section->is_visible ? 'checked' : '' }}
                          />
                          <span class="toggle__slider"></span>
                        </label>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <div style="padding:1rem 1.5rem">
                <button type="submit" class="btn btn-save">Sektionen speichern</button>
              </div>
            </div>
          </form>

          {{-- Rename Modal Template --}}
          <template id="rename-tpl-{{ $template->id }}">
            <form method="POST" action="{{ route('admin.templates.update', $template) }}">
              @csrf
              @method('PUT')
              <div class="modal-form-grid">
                <div class="modal-form-grid__full">
                  <label>Template-Name</label>
                  <input type="text" name="name" value="{{ $template->name }}" maxlength="100" required />
                </div>
              </div>
              <div class="modal__actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal()">Abbrechen</button>
                <button type="submit" class="btn btn-save">Speichern</button>
              </div>
            </form>
          </template>

        </div>
      @endforeach
    </div>
  @endif
@endsection

@push('scripts')
<script>
  document.querySelectorAll('.seasons-tabs__tab').forEach(tab => {
    tab.addEventListener('click', () => {
      const targetId = tab.dataset.tab;

      document.querySelectorAll('.seasons-tabs__tab').forEach(t => {
        t.classList.remove('is-active');
        t.setAttribute('aria-selected', 'false');
      });
      document.querySelectorAll('.seasons-tabs__panel').forEach(p => p.classList.remove('is-active'));

      tab.classList.add('is-active');
      tab.setAttribute('aria-selected', 'true');
      document.getElementById(targetId)?.classList.add('is-active');
    });
  });
</script>
@endpush
