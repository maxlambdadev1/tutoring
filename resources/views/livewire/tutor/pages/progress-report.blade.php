<div x-data="progress_report_init">
    @section('title')
    Alchemy
    @endsection
    @section('description')
    @endsection

    <div class="text-center" style="height:100vh;">
        <div class="container">
            <div class="row">
                <div x-show="!result" class="col-12 mt-4 mt-md-5">
                    <h2 class="text-center mb-4">YOUR TWENTY SESSION PROGRESS REPORT</h2>
                    <p class="text-center">Progress reports are a way for parents to include everyone involved in the education journey of the student. We invite them to send it to their classroom teacher, other family members and any one else that may be involved in caring for that student</p>
                    <p class="text-center">For this reason, we ask you be as detailed as possible. If you need to view your goals or previous session feedback you can do so in Dashboard.</p>
                    <p class="text-center">Upon submission it will be reviewed by our team and forwarded on to the parent. This will then trigger your payment rate increase if applicable.</p>
                    <p class="text-center mb-4">If you have any questions please don't hesitate to get in touch. We are here to support you!</p>
                    <form action="#" id="progress_report">
                        <div class="mb-3">
                            <label for="progress_report_q1" class="form-label fw-bold text-center">Overall, how would you describe the previous 10 sessions?</label>
                            <textarea class="form-control" wire:model="q1" id="progress_report_q1" name="progress_report_q1" rows="5" x-model="q1"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="progress_report_q2" class="form-label fw-bold text-center">Based on the goals you originally defined in the first session (which can be accessed in Dashboard > Your students > Goals), how well were these goals achieved?</label>
                            <textarea class="form-control" wire:model="q2" id="progress_report_q2" name="progress_report_q2" rows="5" x-model="q2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="progress_report_q3" class="form-label fw-bold text-center">Let's redefine our goals for the next 10 sessions. What are we going to strive for with this student?</label>
                            <textarea class="form-control" wire:model="q3" id="progress_report_q3" name="progress_report_q3" rows="5" x-model="q3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="progress_report_q4" class="form-label fw-bold text-center">Finally, let's give the student some encouragement. Here are all the great things about this student:</label>
                            <textarea class="form-control" wire:model="q4" id="progress_report_q4" name="progress_report_q4" rows="5" x-model="q4"></textarea>
                        </div>
                        <div class="mt-4 mb-4 text-center">
                            <button type="button" class="btn btn-warning col-12 col-md-6 text-white" x-on:click="showConfirmModal">Submit report</button>
                            <p class="my-2 text-muted">This form will be submitted to {{$parent->parent_name}} throughout an email.</p>
                        </div>
                    </form>
                </div>
                <div x-show="!!result" class="col-12 mt-4 mt-md-5 text-center">
                    <h2>Thank you</h2>
                    <p>Your report has been submitted. You can check the details in your students area</p>
                    <a href="/">Go to dashboard</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade in" id="report-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h3 class="bold w-100">Session report details</h3>
                </div>
                <div class="modal-body">
                    <p class="mb-1 fw-bold">Overall, how would you describe the previous 10 sessions?</p>
                    <p x-text="q1" class="mb-3"></p>
                    <p class="mb-1 fw-bold">Based on the goals you originally defined in the first session (which can be accessed in Dashboard > Your students > Goals), how well were these goals achieved?</p>
                    <p x-text="q2" class="mb-3"></p>
                    <p class="mb-1 fw-bold">Let's redefine our goals for the next 10 sessions. What are we going to strive for with this student?</p>
                    <p x-text="q3" class="mb-3"></p>
                    <p class="mb-1 fw-bold">Finally, let's give the student some encouragement. Here are all the great things about this student:</p>
                    <p x-text="q4" class="mb-3"></p>
                    <div class="text-center">
                        <button type="button" class="btn btn-warning text-white" x-on:click="submitReport" x-show="!loading">Submit</button>
                        <button type="button" class="btn btn-warning text-white" x-show="loading" disabled>
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Submitting...</button>
                        <button type="button" class="btn btn-secondary" x-on:click="cancel">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('progress_report_init', () => ({
            q1: '',
            q2: '',
            q3: '',
            q4: '',
            loading: false,
            result: false,
            showConfirmModal() {
                $('#progress_report').validate({
                    rules: {
                        progress_report_q1: 'required',
                        progress_report_q2: 'required',
                        progress_report_q3: 'required',
                        progress_report_q4: 'required',
                    }
                });
                if ($('#progress_report').valid()) {
                    $('#report-modal').modal('show');
                }
            },
            cancel() {
                $('#report-modal').modal('hide');
            },
            async submitReport() {
                this.loading = true;
                let result = await @this.call('submitReport');
                if (result) this.result = result;
                this.loading = false;
                $('#report-modal').modal('hide');
            }
        }))
    })
</script>