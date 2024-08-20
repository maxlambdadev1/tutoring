<div x-data="() => ({
            session_date: '',
            session_time: '',
            no_session_scheduled: false,
            no_session_scheduled_reason: '',
            no_session_scheduled_additional_info: '',
            loading: false,

            init() {
                let temp = this;
                $('#session_date{{$session->id}}').datepicker({
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                    startDate: new Date(),
                    todayHighlight: true,
                }).on('changeDate', function(e) {
                    var selectedDate = e.format(0, 'dd/mm/yyyy');
                    temp.session_date = selectedDate;
                });
                $('#session_time{{$session->id}}').datetimepicker({
                    format: 'LT',
                }).on('dp.change', function(e) {
                    let time = e.date.format('h:mm A');
                    temp.session_time = time;
                });
            },
            async submit() {
                this.loading = true;
                let result = await @this.call('rescheduleLesson', this.session_date, this.session_time)
                this.loading = false;
                if (result) setTimeout(() => { location.reload(); }, 2000);
            },
        })">
    <div id="rescheduleSessionModal{{$session->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Reschedule session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center mb-4">Let's reschedule</h3>
                    <form action="#" id="confirm_session_form{{$session->id}}">
                        <div x-show="!no_session_scheduled">
                            <div class="mb-2" id="container_session_date{{$session->id}}">
                                <label for="session_date{{$session->id}}" class="custom_label">Session Date: </label>
                                <input type="text" name="session_date{{$session->id}}" class="form-control session_date{{$session->id}}" id="session_date{{$session->id}}" x-model="session_date">
                            </div>
                            <div class="mb-2" id="container_session_time{{$session->id}}">
                                <label for="session_time{{$session->id}}" class="custom_label">Session Time: </label>
                                <input type="text" name="session_time{{$session->id}}" class="form-control session_time{{$session->id}}" id="session_time{{$session->id}}" x-model="session_time">
                            </div>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" id="no_session_scheduled{{$session->id}}" name="no_session_scheduled" class="form-check-input" x-model="no_session_scheduled" wire:model="no_session_scheduled">
                            <label for="no_session_scheduled{{$session->id}}" class="form-check-label">There is no session scheduled</label>
                        </div>
                        <div x-show="!!no_session_scheduled">
                            <div class="mb-2">
                                <select id="no-session-scheduled-reason{{$session->id}}" name="no-session-scheduled-reason" class="form-select" x-model="no_session_scheduled_reason" wire:model="no_session_scheduled_reason">
                                    <option value="" selected="">Please select an option</option>
                                    <option value="Waiting confirmation from student/parent">Waiting confirmation from student/parent</option>
                                    <option value="I am unavailable">I am unavailable</option>
                                    <option value="Student is taking a break">Student is taking a break</option>
                                    <option value="Student is no longer continuit with tutoring">Student is no longer continuing with tutoring</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="no_session_scheduled_additional_info{{$session->id}}" class="form-label">Additional information (optional)</label>
                                <textarea class="form-control" id="no_session_scheduled_additional_info{{$session->id}}" rows="3" wire:model="no_session_scheduled_additional_info" x-model="no_session_scheduled_additional_info"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning text-white" x-on:click="submit" x-show="!loading" data-bs-dismiss="modal" :disabled="!no_session_scheduled && (!session_date || !session_time) || !!no_session_scheduled && (!no_session_scheduled_reason || !no_session_scheduled_additional_info)">Submit</button>
                    <button type="button" class="btn btn-warning text-white" x-show="loading" disabled>
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Submitting...</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>