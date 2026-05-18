<div class="{{ $columnClass ?? 'col-md-6 col-xl-3' }}">
    <div class="card h-100">
        <div class="card-body">
            <div class="text-muted small mb-1">{{ $title }}</div>
            <div class="display-6 fw-bold">{{ $value }}</div>
            <div class="small text-muted">{{ $description }}</div>
            @if (!empty($actionRoute) && !empty($actionLabel))
                <a href="{{ $actionRoute }}" class="btn btn-link btn-sm px-0 mt-2">{{ $actionLabel }}</a>
            @endif
        </div>
    </div>
</div>
