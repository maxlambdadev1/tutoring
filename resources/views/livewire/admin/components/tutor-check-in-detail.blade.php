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
                <div class="col-12">
                    <div class="other-action">
                        <input type="button" value="Add follow up" data-bs-toggle="modal" data-bs-target="#addFollowupTutorModal{{$row->id}}" class="btn btn-success btn-sm w-100">
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
    <div id="addFollowupTutorModal{{$row->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog" x-data="{in_six_weeks{{$row->id}} : true }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Follow up on this tutor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex mb-3">
                        <span class="form-label fw-bold">In 6 weeks &nbsp;</span>
                        <div>
                            <input type="checkbox" id="in_six_weeks{{$row->id}}" checked name="in_six_weeks{{$row->id}}" data-switch="success" x-ref="in_six_weeks{{$row->id}}" x-on:change="in_six_weeks{{$row->id}} = !in_six_weeks{{$row->id}}">
                            <label for="in_six_weeks{{$row->id}}" data-on-label="Yes" data-off-label="No"></label>
                        </div>
                    </div>
                    <div x-show="in_six_weeks{{$row->id}} == false" class="mb-3">
                        <label for="schedule_date{{$row->id}}" class="form-label">Schedule Date</label>
                        <input type="text" class="form-control " name="schedule_date{{$row->id}}" id="schedule_date{{$row->id}}" x-ref="schedule_date{{$row->id}}">
                    </div>
                </div>
                <div class="modal-footer" x-data="{ init() { 
                    $('#schedule_date{{$row->id}}').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            todayHighlight: true,
                        });
                    } }">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" 
                        wire:click="tutorCheckInAddFollowup({{$row->id}}, in_six_weeks{{$row->id}}, $refs.schedule_date{{$row->id}}.value)"
                        data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>