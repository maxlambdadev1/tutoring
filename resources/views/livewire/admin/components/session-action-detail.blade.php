<div>
    <div class="row mt-3">
        <div class="col-7">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="mb-2">
                        <label for="comment{{$session->id}}" class="form-label">Comment</label>
                        <textarea class="form-control" x-ref="comment{{$session->id}}" id="comment{{$session->id}}" rows="5"></textarea>
                    </div>
                    <input type="button" wire:click="addComment({{$session->id}}, $refs.comment{{$session->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="other-action">
                        @if ($session->session_status == 1)
                        <input type="button" value="Edit Session" data-bs-toggle="modal" data-bs-target="#editSessionModal{{$session->id}}" class="btn btn-primary btn-sm">
                        <input type="button" value="Add penalty" wire:click="addPenalty({{$session->id}})" class="btn btn-warning btn-sm">
                        <input type="button" value="Send tutor email" wire:click="tutorUnconfirmedWarningEmail({{$session->id}})" class="btn btn-info btn-sm">
                        <input type="button" value="Send tutor SMS" wire:click="tutorUnconfirmedWarningSms({{$session->id}})" class="btn btn-success btn-sm">
                        <input type="button" value="Delete session" data-bs-toggle="modal" data-bs-target="#deleteSessionModal{{$session->id}}" class="btn btn-danger btn-sm">
                        @elseif($session->session_status == 3)
                        <input type="button" value="Edit Session" data-bs-toggle="modal" data-bs-target="#editSessionModal{{$session->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm w-50">
                        <input type="button" value="Delete session" data-bs-toggle="modal" data-bs-target="#deleteSessionModal{{$session->id}}" class="btn btn-outline-danger waves-effect waves-light btn-sm w-50">
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5 history-detail">
            @forelse ($session->history as $item)
            <div class="mb-1">
                <div>{{ $item->comment}}</div>
                <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
            </div>
            @empty
            There are no any comments for this lead yet.
            @endforelse
        </div>
    </div>
    <div id="editSessionModal{{$session->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Edit session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="session_date{{$session->id}}" class="form-label">Session date</label>
                        <input type="text" class="form-control " name="session_date{{$session->id}}" value="{{$session->session_date}}" id="session_date{{$session->id}}" x-ref="session_date{{$session->id}}">
                    </div>
                    <div class="mb-3">
                        <label for="session_time{{$session->id}}" class="form-label">Session time</label>
                        <input type="text" class="form-control " name="session_time{{$session->id}}" value="{{$session->session_time_ampm}}" id="session_time{{$session->id}}" x-ref="session_time{{$session->id}}">
                    </div>
                    <div class="alert alert-warning" role="alert">
                        Warning : changing session date and time will move this session to Upcoming or Unconfirmed sessions
                    </div>
                </div>
                <div class="modal-footer" x-data="{ init() { 
                    $('#session_date{{$session->id}}').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            todayHighlight: true,
                        });
                    $('#session_time{{$session->id}}').datetimepicker({
                        format: 'LT',                       
                    });
                    } }">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="editSession({{$session->id}}, $refs.session_date{{$session->id}}.value, $refs.session_time{{$session->id}}.value)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <livewire:admin.components.delete-session-modal ses_id="{{$session->id}}" :key="$session->id" />
</div>