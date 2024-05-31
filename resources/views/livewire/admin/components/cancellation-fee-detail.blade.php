<div class="row">
    <div class="col-6">
        <div class="row mb-2">
            <div class="col-12">
                <div class="mb-2">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control" x-ref="comment{{$row->id}}" id="comment" rows="3"></textarea>
                </div>
                <input type="button" wire:click="addComment({{$row->id}}, $refs.comment{{$row->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="other-action">
                    <input type="button" value="Accept" wire:click="acceptCancellationFee({{$row->id}})" class="btn btn-outline-primary waves-effect waves-light btn-sm w-50">
                    <input type="button" value="Reject" wire:click="rejectCancellationFee({{$row->id}})" class="btn btn-outline-warning waves-effect waves-light btn-sm w-50">
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 history-detail">
        @forelse ($row->history as $item)
        <div class="mb-1">
            <div>{{ $item->comment}}</div>
            <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
        </div>
        @empty
        There are no any comments for this lead yet.
        @endforelse
    </div>
</div>