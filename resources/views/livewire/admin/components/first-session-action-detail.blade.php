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
                    @if($type !== 'daily')
                    <div class="other-action mb-2">
                        <input type="button" value="Make not continuing" class="btn btn-primary btn-sm w-25" x-on:click="function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Confirm session status change',
                                text: 'This will place this session to Not continuing. Are you sure about this?',
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    @this.call('makeSessionNotContinuing1', {{$session->id}});
                                }
                            })}">
                        <input type="button" value="Make student inactive" data-bs-toggle="modal" data-bs-target="#makeStudentInactiveModal{{$session->child->id}}" class="btn btn-warning btn-sm w-25">
                        <input type="button" value="Add second session" data-bs-toggle="modal" data-bs-target="#addSecondSessionModal{{$session->id}}" class="btn btn-success btn-sm w-25">
                    </div>
                    <div class="other-action">
                        <input type="button" value="Send email" data-bs-toggle="modal" data-bs-target="#sendEmailToParentModal{{$session->id}}" class="btn btn-info btn-sm w-25">
                        <input type="button" value="Send tutor update"  class="btn btn-secondary waves-effect waves-light btn-sm w-25"  x-on:click="function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Send tutor update',
                                text: 'This will fire an SMS to the tutor. Are you sure about this?',
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    @this.call('firstSessionTutorUpdate', {{$session->id}});
                                }
                            })}">
                        <input type="button" value="Delete session" data-bs-toggle="modal" data-bs-target="#deleteSessionModal{{$session->id}}" class="btn btn-danger btn-sm w-25">
                    </div>
                </div>
                @else
                <div class="other-action mb-2">
                    <input type="button" value="Make not continuing" class="btn btn-primary btn-sm w-25" x-on:click="function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Confirm session status change',
                                text: 'This will place this session to Not continuing. Are you sure about this?',
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    @this.call('makeSessionNotContinuing1', {{$session->id}});
                                }
                            })}">
                    <input type="button" value="Make student inactive" data-bs-toggle="modal" data-bs-target="#makeStudentInactiveModal{{$session->child->id}}" class="btn btn-warning btn-sm w-25">
                    <input type="button" value="Add second session" data-bs-toggle="modal" data-bs-target="#addSecondSessionModal{{$session->id}}" class="btn btn-success btn-sm w-25">
                    <input type="button" value="Send email" data-bs-toggle="modal" data-bs-target="#sendEmailToParentModal{{$session->id}}" class="btn btn-info btn-sm w-25">
                    <input type="button" value="Delete session" data-bs-toggle="modal" data-bs-target="#deleteSessionModal{{$session->id}}" class="btn btn-danger btn-sm w-25">
                </div>
                @endif
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
    <div id="addSecondSessionModal{{$session->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Add second session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Tutor name</div>
                        <div class="col-8">{{ $session->tutor->tutor_name}}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Student name</div>
                        <div class="col-8">{{ $session->child->child_name}}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold">Session details</div>
                        <div class="col-8">{{ $session->session_length}} hour(s) session on {{$session->session_date}} at {{$session->session_time}}</div>
                    </div>
                    <hr />
                    <div class="mb-3">
                        <label for="session_date{{$session->id}}" class="form-label">Session date</label>
                        <input type="text" class="form-control " name="session_date{{$session->id}}" id="session_date{{$session->id}}" x-ref="session_date{{$session->id}}">
                    </div>
                    <div class="mb-3">
                        <label for="session_time{{$session->id}}" class="form-label">Session time</label>
                        <input type="text" class="form-control " name="session_time{{$session->id}}" id="session_time{{$session->id}}" x-ref="session_time{{$session->id}}">
                    </div>
                    <div class="d-flex mb-3">
                        <span class="form-label fw-bold">Send payment info email &nbsp;</span>
                        <div>
                            <input type="checkbox" id="payment-info{{$session->id}}" name="payment-info{{$session->id}}" data-switch="success" x-ref="payment_info{{$session->id}}" >
                            <label for="payment-info{{$session->id}}" data-on-label="Yes" data-off-label="No"></label>
                        </div>
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
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="addSecondSession({{$session->id}}, $refs.session_date{{$session->id}}.value, $refs.session_time{{$session->id}}.value, $refs.payment_info{{$session->id}}.checked)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div id="sendEmailToParentModal{{$session->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Send email to parent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex mb-3">
                        <span class="form-label fw-bold">Send payment info email &nbsp;</span>
                        <div>
                            <input type="checkbox" id="payment-info-1{{$session->id}}" name="payment-info-1{{$session->id}}" data-switch="success" x-ref="payment_info_1{{$session->id}}" >
                            <label for="payment-info-1{{$session->id}}" data-on-label="Yes" data-off-label="No"></label>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <span class="form-label fw-bold"> Send 'How was the first session?' email &nbsp;</span>
                        <div>
                            <input type="checkbox" id="first-session-info{{$session->id}}" name="first-session-info{{$session->id}}" data-switch="success" x-ref="first_session_info{{$session->id}}" >
                            <label for="first-session-info{{$session->id}}" data-on-label="Yes" data-off-label="No"></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="sendEmailToParent({{$session->id}}, $refs.payment_info_1{{$session->id}}.checked, $refs.first_session_info{{$session->id}}.checked)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <livewire:admin.components.delete-session-modal ses_id="{{$session->id}}" :key="$session->id" />
    <livewire:admin.components.make-student-inactive-modal child_id="{{$session->child->id}}" :key="$session->id" />
</div>