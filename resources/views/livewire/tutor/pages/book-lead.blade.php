<div x-data="init" class="pt-5">
    @section('title')
    Alchemy Tuition - Book your first lesson
    @endsection
    @section('description')
    @endsection

    <div class="container pt-0 pt-md-3" style="min-height: calc(100vh - 190px);">
        <template x-if="step == 1">
            <div class="pb-4 pb-md-5">
                <h2 class="text-center pb-3 my-0">Book your first lesson with an Alchemy tutor</h2>
                <p class="fw-bold mb-4 mb-md-5 text-center">Our booking process is super easy and will just take a few minutes:</p>
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-4 text-center">
                        <img src="/images/one.png" width="80" />
                        <p class="fw-bold my-4">You book.</p>
                        <p>Let us know about you, your child and how we can help.</p>
                        <p>We want to learn as much as we can about your child and what has bought you to us - the more detailed you can be, the better our tutor match can be!</p>
                        <p><b>No payment details are required upfront</b> - this is all discussed after the first lesson.</p>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-4 text-center">
                        <img src="/images/two.png" width="80" />
                        <p class="fw-bold my-4">We match.</p>
                        <p>We talk with our team to organise a tutor that best matches your child's needs.</p>
                        <p>Once we've confirmed the first lesson, we'll send you through their profile by email and they'll give you a call to introduce themselves.</p>
                        <p>We get most students paired up with a tutor within 72 hours, but we'll keep you updated if there are delays.</p>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-4 text-center">
                        <img src="/images/three.png" width="80" />
                        <p class="fw-bold my-4">The magic happens.</p>
                        <p>The first lesson arrives!</p>
                        <p>This lesson is designed for your tutor to see how your child is currently doing - so they'll come prepared with our first session assessment to go through.</p>
                        <p>We'll check in with you after the session - you can schedule in your next lesson and submit payment information easily from there.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn bg-custom-warning text-white" x-on:click="goStep(2)">Get Started</button>
                    </div>
                </div>
            </div>
        </template>
        <template x-if="step == 2">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <label for="who_you_are" class="form-label fw-bold">First up, who are we organising a tutor for?</label>
                    <select name="who_you_are" id="who_you_are" class="form-select" wire:model="who_you_are" x-model="who_you_are">
                        <option value=""></option>
                        <option value="Child">My child (I am the parent)</option>
                        <option value="Me">Me (I am the student)</option>
                        <option value="Someone">Someone else (I am a carer, teacher, organisation or family member)</option>
                    </select>
                </div>
                <div class="col-md-12 mb-5">
                    <button type="button" class="btn bg-light" x-on:click="goStep(1)">Previous</button>
                    <button type="button" class="btn bg-custom-warning text-white" x-on:click="goStep(3)">Next</button>
                </div>
            </div>
        </template>
        <template x-if="step == 3">
            <form action="#" id="parent_contact">
                <div class="row mb-4">
                    <div class="col-12">
                        <div x-show="who_you_are != 'Me'">
                            <h2>Your details:</h2>
                            <p>(This is you, the parent or carer - we will ask more about your child next)</p>
                        </div>
                        <div x-show="who_you_are == 'Me'">
                            <h2>Your parent or carer's details:</h2>
                            <p>We will send them some details about working with us and ensure they are in the loop.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-4">
                        <label for="parent_first_name" class="custom_label">First name: <span style="color: red;">*</span></label>
                        <input type="text" name="parent_first_name" class="form-control" id="parent_first_name" wire:model="parent_first_name" x-model="parent_first_name">
                    </div>
                    <div class="col-md-6 mb-3 mb-md-4">
                        <label for="parent_last_name" class="custom_label">Last name: <span style="color: red;">*</span></label>
                        <input type="text" name="parent_last_name" class="form-control" id="parent_last_name" wire:model="parent_last_name" x-model="parent_last_name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-4">
                        <label for="parent_phone" class="custom_label">Phone number: <span style="color: red;">*</span></label>
                        <input type="text" name="parent_phone" class="form-control" id="parent_phone" wire:model="parent_phone" x-model="parent_phone">
                    </div>
                    <div class="col-md-6  mb-3 mb-md-4">
                        <label for="parent_email" class="custom_label">Email address: <span style="color: red;">*</span></label>
                        <input type="email" name="parent_email" class="form-control" id="parent_email" wire:model="parent_email" x-model="parent_email">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-4">
                        <label for="state_id" class="custom_label">What state are you in</label>
                        <select name="state_id" id="state_id" class="form-select" wire:model="state_id" x-model="state_id">
                            <option value=""></option>
                            @forelse ($states as $state)
                            <option value="{{$state->id}}">{{$state->name}}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-6 mb-3 mb-md-4">
                        <label for="postcode" class="custom_label">What is your postcode? <span style="color: red;">*</span></label>
                        <input type="text" name="postcode" class="form-control" id="postcode" wire:model="postcode" x-model="postcode">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-5">
                        <label for="referral" class="custom_label">If you were referred by a friend, please let us know who:</label>
                        <input type="text" name="referral" class="form-control" id="referral" wire:model="referral" x-model="referral">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <button type="button" class="btn bg-light" x-on:click="goStep(2)">Previous</button>
                        <button type="button" class="btn bg-custom-warning text-white" x-on:click="toStep(4)">Next</button>
                    </div>
                </div>
            </form>
        </template>
        <template x-if="step == 4">
            <div>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="parent_desc">
                            <h2>Student Details:</h2>
                            <p><strong>How many children would you like to book a tutor for?</strong></p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-3 mb-3">
                        <div class="p-1 cursor-pointer position-relative" :class="{'shadow-lg border border-warning':students == 1}" x-on:click="students = 1">
                            <img src="images/one_student.jpg" alt="ONE STUDENT" width="100%">
                            <p class="my-2 text-center">ONE STUDENT</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-1 cursor-pointer" :class="{'shadow-lg border border-warning':students == 2}" x-on:click="students = 2">
                            <img src="images/two_student.jpg" alt="TWO STUDENT" width="100%">
                            <p class="my-2 text-center">TWO STUDENT</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="p-1 cursor-pointer" :class="{'shadow-lg border border-warning':students == 3}" x-on:click="students = 3">
                            <img src="images/three_student.jpg" alt="THREE STUDENT" width="100%">
                            <p class="my-2 text-center">THREE STUDENT</p>
                        </div>
                    </div>
                </div>
                <form action="#" id="student_details_one_1">
                    <div class="row">
                        <div class="col-12 mb-3" x-show="students > 1">
                            <h3 class="text-custom-warning">First student details:</h3>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="first_name" class="custom_label">Student first name: <span style="color: red;">*</span></label>
                            <input type="text" name="first_name" class="form-control" id="first_name" wire:model="student1.student_first_name" x-model="student1.student_first_name">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="last_name" class="custom_label">Student last name: <span style="color: red;">*</span></label>
                            <input type="text" name="last_name" class="form-control" id="last_name" wire:model="student1.student_last_name" x-model="student1.student_last_name">
                        </div>
                    </div>
                </form>
            </div>
        </template>
    </div>
    <div class="container-fluid bg-black text-center py-4">
        <img src="/images/success_logo.png" width="100" />
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('init', () => ({
            step: 4,
            who_you_are: '',
            parent_first_name: '',
            parent_last_name: '',
            parent_phone: '',
            parent_email: '',
            postcode: '',
            state_id: '',
            referral: '',
            students: 1,
            student1: {
                'student_first_name' : '',
                'student_last_name' : '',
                'grade_id' : '',
                'student_school' : '',
                'start_date' : '',
                'start_date_picker' : '',
                'subject' : '',
                'availabilities' : [],
                'parent_looking' : '',
                'student_doing' : '',
                'additional_details' : '',
                'main_result' : '',
                'student_performance' : '',
                'student_attitude' : '',
                'student_mind' : '',
                'student_personality' : '',
                'student_favourite' : '',
            },
            goStep(step) {
                this.step = step;
            },
            toStep(step) {
                switch (step) {
                    case 4:
                        $('#parent_contact').validate({
                            rules: {
                                parent_first_name: 'required',
                                parent_last_name: 'required',
                                parent_email: {
                                    required: true,
                                    email: true,
                                },
                                parent_phone: {
                                    required: true,
                                    digits: true,
                                    minlength: 10,
                                    maxlength: 11
                                },
                                postcode: {
                                    required: true,
                                    digits: true,
                                    minlength: 4,
                                    maxlength: 4,
                                },

                            }
                        });
                        if ($('#parent_contact').valid()) {
                            this.goStep(4);
                        }
                        break;
                    default:
                }
            }
        }))
    })
</script>