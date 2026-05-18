<div class="card-header {{ $wrapperClass ?? '' }}">
    <div>
        <h4 class="card-title mb-1">{{ $title }}</h4>
        @if (!empty($description))
            <small class="text-muted">{{ $description }}</small>
        @endif
    </div>
    @isset($badge)
        <div>{!! $badge !!}</div>
    @endisset
</div>
