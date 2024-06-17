<div class="container mx-0">
    <div class="row mt-3">
        <div class="col-6">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="mb-2">
                        <label for="comment{{$row->id}}" class="form-label">Comment</label>
                        <textarea class="form-control" x-ref="comment{{$row->id}}" id="comment{{$row->id}}" rows="5"></textarea>
                    </div>
                    <input type="button" wire:click="addComment({{$row->id}}, $refs.comment{{$row->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                </div>
            </div>
        </div>
        <div class="col-6 history-detail">
            @forelse ($row->tutor['history'] as $item)
            <div class="mb-1">
                <div>{{ $item['comment']}}</div>
                <span class="text-muted"><small>{{ $item['author'] }} on {{ $item['date'] }}</small></span>
            </div>
            @empty
            There are no any comments for this recruiter yet.
            @endforelse
        </div>
    </div>
</div>