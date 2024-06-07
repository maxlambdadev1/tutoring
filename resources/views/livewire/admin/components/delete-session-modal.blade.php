<div>
    <div id="deleteSessionModal{{$session->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog" x-data="{is_student_send_to_inactive{{$session->id}} : false, disable_future_follow_up{{$session->id}} : false }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Delete session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
