<div>
    <div id="makeTutorInactiveModal{{$tutor->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog" x-data="{is_now{{$tutor->id}} : true }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Make tutor inactive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason{{$tutor->id}}" class="form-label">Reason</label>
                        <textarea class="form-control " name="reason{{$tutor->id}}" id="reason{{$tutor->id}}" x-ref="reason{{$tutor->id}}" rows="3" ></textarea>
                    </div>
                    <div class="d-flex mb-3">
                        <span class="form-label fw-bold">Now &nbsp;</span>
                        <div>
                            <input type="checkbox" id="is_now{{$tutor->id}}" checked name="is_now{{$tutor->id}}" data-switch="success" x-ref="is_now{{$tutor->id}}" x-on:change="is_now{{$tutor->id}} = !is_now{{$tutor->id}}">
                            <label for="is_now{{$tutor->id}}" data-on-label="Yes" data-off-label="No"></label>
                        </div>
                    </div>
                    <div x-show="is_now{{$tutor->id}} == false" class="mb-3">
                        <label for="schedule_date{{$tutor->id}}" class="form-label">Schedule Date</label>
                        <input type="text" class="form-control " name="schedule_date{{$tutor->id}}" id="schedule_date{{$tutor->id}}" x-ref="schedule_date{{$tutor->id}}">
                    </div>
                </div>
                <div class="modal-footer" x-data="{ init() { 
                    $('#schedule_date{{$tutor->id}}').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            todayHighlight: true,
                        });
                    } }">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" 
                        wire:click="makeTutorInactive({{$tutor->id}}, is_now{{$tutor->id}}, $refs.schedule_date{{$tutor->id}}.value, $refs.reason{{$tutor->id}}.value)"
                        data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>
