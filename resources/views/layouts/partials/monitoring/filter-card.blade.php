<div class="card mb-4">
    @include('layouts.partials.monitoring.card-header', [
        'title' => $title,
        'description' => $description ?? null,
    ])
    <div class="card-body">
        <form method="GET" action="{{ $action }}">
            <div class="row g-3 align-items-end">
                @foreach ($fields as $field)
                    <div class="{{ $field['columnClass'] ?? 'col-md-3' }}">
                        <label class="form-label">{{ $field['label'] }}</label>

                        @if (($field['type'] ?? 'text') === 'select')
                            <select name="{{ $field['name'] }}" class="form-select">
                                <option value="">{{ $field['placeholder'] ?? 'Semua' }}</option>
                                @foreach (($field['options'] ?? []) as $option)
                                    <option value="{{ $option['value'] }}" {{ (string) ($field['value'] ?? '') === (string) $option['value'] ? 'selected' : '' }}>
                                        {{ $option['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input
                                type="{{ $field['type'] ?? 'text' }}"
                                name="{{ $field['name'] }}"
                                class="form-control"
                                value="{{ $field['value'] ?? '' }}"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                            >
                        @endif
                    </div>
                @endforeach

                <div class="{{ $submitColumnClass ?? 'col-md-1' }} d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>

                <div class="col-md-12">
                    <a href="{{ $resetRoute }}" class="btn btn-light btn-sm">Reset Filter</a>
                </div>
            </div>
        </form>
    </div>
</div>
