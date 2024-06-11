<div class="container mx-0">
    <div class="row mt-3">
        <div class="col-6">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="mb-2">
                        <label for="comment{{$row->session_id}}" class="form-label">Comment</label>
                        <textarea class="form-control" x-ref="comment{{$row->session_id}}" id="comment{{$row->session_id}}" rows="5"></textarea>
                    </div>
                    <input type="button" wire:click="addComment({{$row->session_id}}, $refs.comment{{$row->session_id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="other-action">
                        <input type="button" value="Hide" wire:click="editRescheduleSession({{$row->session_id}})" class="btn btn-outline-secondary waves-effect waves-light btn-sm w-100">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 history-detail">
            @forelse ($row->session->history as $item)
            <div class="mb-1">
                <div>{{ $item->comment}}</div>
                <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
            </div>
            @empty
            There are no any comments for this lead yet.
            @endforelse
        </div>
    </div>
</div>