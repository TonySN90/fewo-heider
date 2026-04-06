@extends('layouts.admin')

@section('title', 'Seitenstruktur')

@section('content')
  <div class="page-header">
    <h1>Seitenstruktur</h1>
  </div>

  <div class="seasons-tabs">
    <form method="POST" action="{{ route('admin.page-structure.sections') }}">
      @csrf
      @method('PUT')

      <div class="table-card" style="box-shadow:none;margin-bottom:0;border-radius:0">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Sektion</th>
              <th>Sichtbar</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($sections as $section)
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
                <td>
                  @if (in_array($section->section_key, $editableSections))
                    <a href="{{ route('admin.page-structure.edit', $section->section_key) }}" class="btn btn-edit">
                      <span class="material-symbols-rounded">edit</span>
                    </a>
                  @endif
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
  </div>
@endsection
