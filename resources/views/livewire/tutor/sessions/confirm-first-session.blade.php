<div x-data="confirm_first_session_init">
    @php
    $title = "Confirm sessions";
    $breadcrumbs = ["Sessions", "Confirm"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="text-center mb-3 mb-md-4">Confirm your first session with {{$session->child->child_name ?? ''}} on {{$session->session_date}} at {{$session->session_time}}</h2>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 text-center">
                            <form action="#" id="confirm_first_session_form">
                                <div x-show="step == 1">
                                    <p>How long was this lesson? <span style="color: red;">*</span></p>
                                    <div class="d-flex mb-3">
                                        <select name="session_hours" id="session_hours" class="form-select" wire:model="session_hours" x-model="session_hours">
                                            <option value="0">0 Hour</option>
                                            <option value="1">1 Hour</option>
                                            <option value="2">2 Hour</option>
                                            <option value="3">3 Hour</option>
                                            <option value="4">4 Hour</option>
                                            <option value="5">5 Hour</option>
                                            <option value="6">6 Hour</option>
                                            <option value="7">7 Hour</option>
                                            <option value="8">8 Hour</option>
                                            <option value="9">9 Hour</option>
                                            <option value="10">10 Hour</option>
                                        </select>
                                        <select name="session_minutes" id="session_minutes" class="form-select" wire:model="session_minutes" x-model="session_minutes">
                                            <option value="0">0 Minutes</option>
                                            <option value="0.25">15 Minutes</option>
                                            <option value="0.5">30 Minutes</option>
                                            <option value="0.75">45 Minutes</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="session_type_id" class="form-label">How was the session held? <span style="color: red;">*</span></label>
                                        <select name="session_type_id" id="session_type_id" class="form-select" wire:model="session_type_id" x-model="session_type_id">
                                            <option value=""></option>
                                            <option value="1">Face to Face</option>
                                            <option value="2">Online</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <button type="button" :disabled="session_type_id == '' || session_hours + session_minutes == 0" class="btn btn-warning" x-on:click="toStep(2)">Next</button>
                                    </div>
                                </div>
                                <div x-show="step == 2">
                                    <div class="mb-3">
                                        <div class="mb-2">
                                            <label for="question_1" class="form-label">What are the main area of focus for the student? <span style="color: red;">*</span></label>
                                            <textarea class="form-control" wire:model="question_1" id="question_1" name="question_1" rows="3" x-model="question_1"></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label for="question_2" class="form-label">What is the main goal to work towards over the next 10 weeks? <span style="color: red;">*</span></label>
                                            <textarea class="form-control" wire:model="question_2" id="question_2" name="question_2" rows="3" x-model="question_2"></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label for="question_3" class="form-label mb-0">How will you achieve this goal? <span style="color: red;">*</span></label>
                                            <p class="text-muted">(Remember that these answers are sent directly to the parent)</p>
                                            <textarea class="form-control" wire:model="question_3" id="question_3" name="question_3" rows="3" x-model="question_3"></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label for="question_4" class="form-label mb-0">What positive attributes does the student have? <span style="color: red;">*</span></label>
                                            <p class="text-muted">Talk about the potential they have and how great they are to work with</p>
                                            <textarea class="form-control" wire:model="question_4" id="question_4" name="question_4" rows="3" x-model="question_4"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <button type="button" class="btn bg-light" x-on:click="goStep(1)">Previous</button>
                                            <button type="button" class="btn btn-warning" x-on:click="toStep(3)">Next</button>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="step == 3">
                                    <div class="mb-3">
                                        <h3>Your next session</h3>
                                        <p>If you have spoken to the parent about the second session please enter the proposed time and date here, however this will not appear in your Dashboard until we have confirmed it with them. If you do not know, leave them blank and we will find out directly from the parent.</p>
                                        <div class="d-flex">
                                            <div class="mb-3 flex-grow-1" id="container_session_date">
                                                <label for="session_date" class="custom_label">Session Date: <span style="color: red;">*</span></label>
                                                <input type="text" name="session_date" class="form-control session_date" id="session_date" x-model="session_date">
                                            </div>
                                            <div class="mb-3 flex-grow-1" id="container_session_time">
                                                <label for="session_time" class="custom_label">Session Time: <span style="color: red;">*</span></label>
                                                <input type="text" name="session_time" class="form-control session_time" id="session_time" x-model="session_time">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <template x-if="session_type_id == 1">
                                                <div class="form-check text-start">
                                                    <input type="checkbox" id="confirm_first_session_type" name="confirm_first_session_type" class="form-check-input" x-model="confirm_first_session_type">
                                                    <label for="confirm_first_session_type" class="form-check-label">This lesson was <b>Face to Face</b>. <span style="color: red;">*</span></label>
                                                </div>
                                            </template>
                                            <template x-if="session_type_id == 2">
                                                <div class="form-check text-start">
                                                    <input type="checkbox" id="confirm_first_session_type" name="confirm_first_session_type" class="form-check-input" x-model="confirm_first_session_type">
                                                    <label for="confirm_first_session_type" class="form-check-label">This lesson was <b>Online</b>. <span style="color: red;">*</span></label>
                                                </div>
                                            </template>
                                            <p class="mb-0 text-start" x-show="!!unconfirmed_session_type" style="color: #900; font-size: 12px;">
                                                Please confirm the session type!
                                            </p>
                                        </div>
                                        <p>You will receive the face to face payment rate as defined in the tutor agreement. This cannot be changed after this point so please ensure it is correct</p>
                                        <div class="mb-3">
                                            <button type="button" class="btn bg-light" x-on:click="goStep(2)">Previous</button>
                                            <button type="button" class="btn btn-primary" x-on:click="submit" x-show="!loading">Submit</button>
                                            <button type="button" class="btn btn-primary text-white" x-show="loading" disabled>
                                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                Submitting...</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var confirm_first_session_init = () => {
        Alpine.data('confirm_first_session_init', () => ({
            step: 1,
            session_hours: 0,
            session_minutes: 0,
            session_type_id: '',
            question_1: '',
            question_2: '',
            question_3: '',
            question_4: '',
            session_date: '',
            session_time: '',
            confirm_first_session_type: false,
            unconfirmed_session_type: false,
            loading: false,
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
            goStep(step) {
                this.step = step;
            },
            toStep(step) {
                switch (step) {
                    case 3:
                        if (!this.form_validate()) return;
                        break;
                }
                this.goStep(step);
            },
            getStudentEngagementRating() {
                this.student_engagement = $('#student_engagement').rateit('value');
            },
            getStudentUnderstandingRating() {
                this.student_understanding = $('#student_understanding').rateit('value');
            },
            getOverallSessionRating() {
                this.overall_session_rating = $('#overall_session_rating').rateit('value');
            },
            form_validate() {
                let flag = true;
                $('#confirm_first_session_form').validate({
                    rules: {
                        session_type_id: 'required',
                        question_1: {
                            required: true,
                            minlength: 50,
                            maxlength: 300,
                        },
                        question_2: {
                            required: true,
                            minlength: 50,
                            maxlength: 300,
                        },
                        question_3: {
                            required: true,
                            minlength: 50,
                            maxlength: 300,
                        },
                        question_4: {
                            required: true,
                            minlength: 50,
                            maxlength: 300,
                        },
                        session_date: 'required',
                        session_time: 'required',
                    }
                });
                if (!$('#confirm_first_session_form').valid()) flag = false;
                if (this.step == 3) {
                    if (!this.confirm_first_session_type) {
                        this.unconfirmed_session_type = true;
                        flag = false;
                    } else this.unconfirmed_session_type = false;
                }

                return flag;
            },
            async submit() {
                if (!this.form_validate()) return;
                this.loading = true;
                let result = await @this.call('confirmFirstSession', this.session_date, this.session_time)
                if (result) setTimeout(() => {
                    location.href = "/sessions/unconfirmed-sessions";
                }, 2000);
                else setTimeout(() => {
                    location.reload();
                }, 3000);
                this.loading = false;
            },
        }))
    }

    if (typeof Alpine == 'undefined') {
        document.addEventListener('alpine:init', () => {
            confirm_first_session_init();
        });
    } else {
        confirm_first_session_init();
    }
</script>