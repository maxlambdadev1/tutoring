<div class="container mx-0">
    <div class="row">
        <div class="col-6">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="mb-2">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" x-ref="comment{{$row->id}}" id="comment" rows="5"></textarea>
                    </div>
                    <input type="button" wire:click="addComment({{$row->id}}, $refs.comment{{$row->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-6">
                    <div>
                        <select class="form-select form-select-sm" id="select-status" x-ref="tutor_first_session_status{{$row->id}}">
                            @foreach ($options['tutor_first_session_status'] as $key => $value)
                            <option value="{{$key}}" {{ $row->status == $key ? 'selected' : ''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="other-action">
                        <input type="button" value="Update status" wire:click="updateTutorFirstSessionStatus({{$row->id}}, $refs.tutor_first_session_status{{$row->id}}.value)" class="btn btn-secondary btn-sm w-100">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 history-detail">
            @forelse ($row->history as $comment)
            <div class="mb-1">
                <div>{{ $comment->comment}}</div>
                <span class="text-muted"><small>{{ $comment->author }} on {{ $comment->date }}</small></span>
            </div>
            @empty
            There are no any comments for this tutor yet.
            @endforelse
        </div>
    </div>
</div>