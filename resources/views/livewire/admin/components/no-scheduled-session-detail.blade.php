<div class="container mx-0">
    @php
    //change array to object
    $session_json = json_encode($row->session);
    $session = json_decode($session_json);
    @endphp

    <div class="row">
        <div class="col-3">
            <x-session-usual-description :session="$session" />

            <div class="row mt-3">
                <div class="col-6">
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
                                <input type="button" value="Change status" data-bs-toggle="modal" data-bs-target="#changeStatusModal{{$session->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm">
                                <input type="button" value="Make student inactive" data-bs-toggle="modal" data-bs-target="#makeStudentInactiveModal{{$session->id}}"  class="btn btn-outline-warning waves-effect waves-light btn-sm">
                                <input type="button" value="Connect new tutor" data-bs-toggle="modal" data-bs-target="#connectNewTutorNoScheduledSessionModal{{$session->id}}"  class="btn btn-outline-secondary waves-effect btn-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 history-detail">
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
        </div>
    </div>
    <div id="changeStatusModal{{$session->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Change status and follow up date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="followup_status{{$session->id}}" class="form-label">Choose an option</label>
                        <select name="followup_status{{$session->id}}" id="followup_status{{$session->id}}" x-ref="followup_status{{$session->id}}" class="form-select" required>
                            <option value="" {{ $row->filter == 'All' ? 'selected' : '' }}>All</option>
                            @foreach ($options['filter_array'] as $key => $item)
                            <option value="{{$key}}" {{ $row->filter == $item ? 'selected' : '' }}>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="followup_date{{$session->id}}" class="form-label">Session date</label>
                        <input type="text" class="form-control " name="followup_date{{$session->id}}" value="{{$row->followup_date}}" id="followup_date{{$session->id}}" x-ref="followup_date{{$session->id}}">
                    </div>
                </div>
                <div class="modal-footer" x-data="{ init() { 
                    $('#followup_date{{$session->id}}').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            todayHighlight: true,
                        });
                    } }">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="changeStatus({{$session->id}}, $refs.followup_status{{$session->id}}.value, $refs.followup_date{{$session->id}}.value)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div id="makeStudentInactiveModal{{$session->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog" x-data="{disable_future_follow_up{{$session->id}} : false }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Make student inactive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="makeStudentToInactive({{$session->child->id}}, $refs.delete_student_reason{{$session->id}}.value, $refs.follow_up{{$session->id}}.value, $refs.disable_future_follow_up_reason{{$session->id}}.value)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <livewire:admin.components.connect-new-tutor-for-no-scheduled-session-modal ses_id="{{$session->id}}" :key="$session->id" />
</div>