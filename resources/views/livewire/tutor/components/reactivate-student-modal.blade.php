<div x-data="reactivate_student_modal_init_{{$child->id}}">
    <div id="reactivateStudentModal{{$child->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Re-activate and Schedule a lesson</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center mb-4">Schedule a lesson with {{$child->child_name}}</h3>
                    <form action="#" id="confirm_session_form{{$child->id}}">
                        <div class="mb-2" id="container_session_date{{$child->id}}">
                            <label for="session_date{{$child->id}}" class="custom_label">Session Date: </label>
                            <input type="text" name="session_date{{$child->id}}" class="form-control session_date{{$child->id}}" id="session_date{{$child->id}}" x-model="session_date">
                        </div>
                        <div class="mb-3" id="container_session_time{{$child->id}}">
                            <label for="session_time{{$child->id}}" class="custom_label">Session Time: </label>
                            <input type="text" name="session_time{{$child->id}}" class="form-control session_time{{$child->id}}" id="session_time{{$child->id}}" x-model="session_time">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning text-white" x-on:click="submit" x-show="!loading" data-bs-dismiss="modal" :disabled="!session_date || !session_time">Submit</button>
                    <button type="button" class="btn btn-warning text-white" x-show="loading" disabled>
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Submitting...</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reactivate_student_modal_init_{{$child->id}}', () => ({
            session_date: '',
            session_time: '',
            loading: false,

            init() {
                let temp = this;
                $('#session_date{{$child->id}}').datepicker({
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                    startDate: new Date(),
                    todayHighlight: true,
                }).on('changeDate', function(e) {
                    var selectedDate = e.format(0, 'dd/mm/yyyy');
                    temp.session_date = selectedDate;
                });
                $('#session_time{{$child->id}}').datetimepicker({
                    format: 'LT',
                }).on('dp.change', function(e) {
                    let time = e.date.format('h:mm A');
                    temp.session_time = time;
                });
            },
            async submit() {
                this.loading = true;
                let result = await @this.call('reactivateAndScheduleLesson', this.session_date, this.session_time)
                if (result) this.result = result;
                this.loading = false;
            },
        }))
    })
</script>