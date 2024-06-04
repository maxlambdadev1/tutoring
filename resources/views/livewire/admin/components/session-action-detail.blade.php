<div>
    <div class="row mt-3">
        <div class="col-7">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="mb-2">
                        <label for="comment{{$session->id}}" class="form-label">Comment</label>
                        <textarea class="form-control" x-ref="comment{{$session->id}}" id="comment{{$session->id}}" rows="3"></textarea>
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
    <div id="deleteSessionModal{{$session->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog"
        x-data="{is_student_send_to_inactive{{$session->id}} : false, disable_future_follow_up{{$session->id}} : false }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Delete session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" >
                    <div class="mb-3">
                        <label for="delete_reason{{$session->id}}" class="form-label">Reason</label>
                        <textarea class="form-control " name="delete_reason{{$session->id}}" id="delete_reason{{$session->id}}" x-ref="delete_reason{{$session->id}}" rows="3" required></textarea>
                    </div>
                    <div class="d-flex">
                        <span class="form-label fw-bold">Would you like to send the student to inactive students? &nbsp;</span>
                        <div>
                            <input type="checkbox" id="is_student_send_to_inactive{{$session->id}}" name="is_student_send_to_inactive{{$session->id}}" data-switch="success" x-ref="is_student_send_to_inactive{{$session->id}}" x-on:change="is_student_send_to_inactive{{$session->id}} = !is_student_send_to_inactive{{$session->id}}">
                            <label for="is_student_send_to_inactive{{$session->id}}" data-on-label="Yes" data-off-label="No"></label>
                        </div>
                    </div>
                    <div x-show="is_student_send_to_inactive{{$session->id}} == true">
                        <div class="mb-3">
                            <label for="delete_student_reason{{$session->id}}" class="form-label">Reason for deleting student.</label>
                            <textarea class="form-control " name="delete_student_reason{{$session->id}}" id="delete_student_reason{{$session->id}}" x-ref="delete_student_reason{{$session->id}}" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="follow_up{{$session->id}}" class="form-label">Follow up</label>
                            <select name="follow_up{{$session->id}}" id="follow_up{{$session->id}}" x-ref="follow_up{{$session->id}}" class="form-select" required>
                                <option value="">Please select...</option>
                                <option value="1">Deleted lead - parent choice</option>
                                <option value="2">Deleted lead - unable to help</option>
                                <option value="3">Cancelled first session (no lessons happened)</option>
                                <option value="4">Cancelled lessons (had regular lessons)</option>
                            </select>
                        </div>
                        <div class="d-flex">
                            <span class="form-label fw-bold">Disable future follow up &nbsp;</span>
                            <div>
                                <input type="checkbox" id="disable_future_follow_up{{$session->id}}" name="disable_future_follow_up{{$session->id}}" data-switch="success" x-ref="disable_future_follow_up{{$session->id}}" x-on:change="disable_future_follow_up{{$session->id}} = !disable_future_follow_up{{$session->id}}">
                                <label for="disable_future_follow_up{{$session->id}}" data-on-label="Yes" data-off-label="No"></label>
                            </div>
                        </div>
                        <div x-show="disable_future_follow_up{{$session->id}} == true" class="mb-3">
                            <label for="disable_future_follow_up_reason{{$session->id}}" class="form-label">Reason for no follow up.</label>
                            <textarea class="form-control " name="disable_future_follow_up_reason{{$session->id}}" id="disable_future_follow_up_reason{{$session->id}}" x-ref="disable_future_follow_up_reason{{$session->id}}" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" x-on:click="function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Are you sure?',
                            text: 'This session will be permanently deleted',
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                @this.call('deleteSession', {{$session->id}}, $refs.delete_reason{{$session->id}}.value, is_student_send_to_inactive{{$session->id}}, $refs.delete_student_reason{{$session->id}}.value, $refs.follow_up{{$session->id}}.value, $refs.disable_future_follow_up_reason{{$session->id}}.value);
                            }
                        })}" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>