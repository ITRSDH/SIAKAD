<div class="card">
    @include('layouts.partials.monitoring.card-header', [
        'title' => $title,
        'description' => $description ?? null,
        'wrapperClass' => $headerWrapperClass ?? null,
        'badge' => $badge ?? null,
    ])
    <div class="card-body">
        @if (!empty($beforeTable))
            {!! $beforeTable !!}
        @endif

        @if (!empty($slot))
            {!! $slot !!}
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            @foreach ($columns as $column)
                                <th>{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) }}" class="text-center text-muted py-4">{{ $emptyText ?? 'Belum ada data.' }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
