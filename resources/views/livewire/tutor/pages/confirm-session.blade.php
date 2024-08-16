<div x-data="confirm_session_init">
    @section('title')
    Confirm your next lesson // Alchemy Tuition
    @endsection
    @section('description')
    @endsection

    <div class="" style="height:100vh;">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div x-show="!result" class="mt-4 mt-md-5">
                        <div class="mb-4">
                            <h2 class="text-center mb-4">Confirm your next session</h2>
                            <p class="text-center">It is so great to know that the first lesson went well!</p>
                            <p class="text-center">Please enter the date and time of your next session below. Unless discussed with your tutor, this will usually be the same day and time as the first session.</p>
                            @if (empty($session->parent->thirdparty_org_id))
                            <p class="text-center">Upon submission you will be taken to our secure payment portal where you can learn about our payment system and enter payment details.</p>
                            @endif
                        </div>
                        <form action="#" id="confirm_session_form">
                            <div class="mb-2" id="container_session_date">
                                <label for="session_date" class="custom_label">Date of your next session: <span style="color: red;">*</span></label>
                                <input type="text" name="session_date" class="form-control session_date" id="session_date" x-model="session_date">
                            </div>
                            <div class="mb-3" id="container_session_time">
                                <label for="session_time" class="custom_label">Time of your next session: <span style="color: red;">*</span></label>
                                <input type="text" name="session_time" class="form-control session_time" id="session_time" x-model="session_time">
                            </div>
                            <div class="mt-3 text-center">
                                <button type="button" class="btn btn-warning text-white col-md-12 mb-2" x-on:click="submit" x-show="!loading">Confirm my next session</button>
                                <button type="button" class="btn btn-warning text-white col-md-3 mb-2" x-show="loading" disabled>
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Submitting...</button>
                            </div>
                            <p class="text-muted text-center">There are no contracts, prepayments or minimum commitments in working with us. You can cancel at any time.</p>
                        </form>
                    </div>
                    <div x-show="!!result && thirdparty_org_id != null" class="mt-4 mt-md-5 text-center">
                        <h2 class="mb-3">Thank you for confirming your next lesson.</h2>
                        <p>From here, if you need to move or cancel any scheduled lessons, please contact your tutor directly.</p>
                        <p>We’ve just sent you an email with their details.</p>
                        <p>If you have any questions, please don’t hesitate to reach out! We are here to help!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let thirdparty_org_id = @json($session->parent->thirdparty_org_id);
    let parent_email = @json($session->parent->parent_email);

    document.addEventListener('alpine:init', () => {
        Alpine.data('confirm_session_init', () => ({
            session_date: '',
            session_time: '',
            thirdparty_org_id: thirdparty_org_id,
            loading: false,
            result: false,
            init() {
                let temp = this;
                $('#session_date').datepicker({
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                    startDate: new Date(),
                    todayHighlight: true,
                }).on('changeDate', function(e) {
                    var selectedDate = e.format(0, 'dd/mm/yyyy');
                    temp.session_date = selectedDate;
                });
                $('#session_time').datetimepicker({
                    format: 'LT',
                }).on('dp.change', function(e) {
                    let time = e.date.format('h:mm A');
                    temp.session_time = time;
                });
            },
            async submit() {
                $('#confirm_session_form').validate({
                    rules: {
                        session_date: 'required',
                        session_time: 'required',
                    }
                });
                if ($('#confirm_session_form').valid()) {
                    this.loading = true;
                    let result = await @this.call('confirmSecondSession', this.session_date, this.session_time);
                    if (result) this.result = result;
                    this.loading = false;
                    if (this.thirdparty_org_id == null) location.href = `/paymentcc?email=${parent_email}`;
                }
            },
        }))
    })
</script>