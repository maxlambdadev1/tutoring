<div class="history-detail px-3">
    @forelse ($row->history as $item)
    <div class="mb-1">
        <div>{{ $item->comment}}</div>
        <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
    </div>
    @empty
    There are no any comments yet.
    @endforelse
</div>