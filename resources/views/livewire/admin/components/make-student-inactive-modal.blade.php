<div>
    <div id="makeStudentInactiveModal{{$child->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog"
        x-data="{is_student_send_to_inactive{{$child->id}} : false, disable_future_follow_up{{$child->id}} : false }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Make student inactive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" >
                        <div class="mb-3">
                            <label for="delete_student_reason{{$child->id}}" class="form-label">Reason for deleting student.</label>
                            <textarea class="form-control " name="delete_student_reason{{$child->id}}" id="delete_student_reason{{$child->id}}" x-ref="delete_student_reason{{$child->id}}" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="follow_up{{$child->id}}" class="form-label">Follow up</label>
                            <select name="follow_up{{$child->id}}" id="follow_up{{$child->id}}" x-ref="follow_up{{$child->id}}" class="form-select" required>
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
                                <input type="checkbox" id="disable_future_follow_up{{$child->id}}" name="disable_future_follow_up{{$child->id}}" data-switch="success" x-ref="disable_future_follow_up{{$child->id}}" x-on:change="disable_future_follow_up{{$child->id}} = !disable_future_follow_up{{$child->id}}">
                                <label for="disable_future_follow_up{{$child->id}}" data-on-label="Yes" data-off-label="No"></label>
                            </div>
                        </div>
                        <div x-show="disable_future_follow_up{{$child->id}} == true" class="mb-3">
                            <label for="disable_future_follow_up_reason{{$child->id}}" class="form-label">Reason for no follow up.</label>
                            <textarea class="form-control " name="disable_future_follow_up_reason{{$child->id}}" id="disable_future_follow_up_reason{{$child->id}}" x-ref="disable_future_follow_up_reason{{$child->id}}" rows="3" required></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" x-on:click="function() {
                        Swal.fire({
                            icon: 'info',
                            title: 'Are you sure?',
                            text: 'Would you like to send the student to inactive students?',
                            showCancelButton: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                @this.call('makeStudentInactive1', {{$child->id}}, $refs.delete_student_reason{{$child->id}}.value, $refs.follow_up{{$child->id}}.value, $refs.disable_future_follow_up_reason{{$child->id}}.value);
                            }
                        })}" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>
