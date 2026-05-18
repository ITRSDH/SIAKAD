<div class="border rounded p-2 mb-2">
    <div class="fw-semibold">{{ $title }}</div>
    @if (!empty($subtitle))
        <div class="small text-muted">{{ $subtitle }}</div>
    @endif
    @if (!empty($status))
        <div class="mt-1">
            @include('layouts.partials.status-badge', ['value' => $status])
        </div>
    @endif
</div>
