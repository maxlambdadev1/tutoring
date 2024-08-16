<div x-data="init" class="pt-5">
    @section('title')
    Alchemy Tuition - Book your first lesson
    @endsection
    @section('description')
    @endsection
    @section('script')    
    <script src="https://maps.googleapis.com/maps/api/js?libraries=maps,places,marker&key=AIzaSyAOIopVJmkbjQFH8B9Sy3RpZLJzUQGjHnY&loading=async" async defer></script>
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
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="custom_label">Student first name: <span style="color: red;">*</span></label>
                            <input type="text" name="first_name" class="form-control" id="first_name" wire:model="student1.student_first_name" x-model="student1.student_first_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="custom_label">Student last name: <span style="color: red;">*</span></label>
                            <input type="text" name="last_name" class="form-control" id="last_name" wire:model="student1.student_last_name" x-model="student1.student_last_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="school" class="custom_label">What school do they go to?</label>
                            <input type="text" name="school" class="form-control" id="school" wire:model="student1.student_school" x-model="student1.student_school">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="grade_id" class="custom_label">What grade are they in? <span style="color: red;">*</span></label>
                            <select name="grade_id" id="grade_id" class="form-control" wire:model="student1.grade_id" x-model="student1.grade_id">
                                <option value=""></option>
                                @foreach ($grades as $grade)
                                <option value="{{$grade->id}}">{{$grade->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="student1_subject" class="custom_label">What subject do they need support in? <span style="color: red;">*</span></label>
                            <p class="sub_label">Please select the subject that best describes their area of need.</p>
                            <select name="student1_subject" id="student1_subject" class="form-control" wire:model="student1.subject" x-model="student1.subject">
                                <option value=""></option>
                                @foreach ($all_subjects as $subject)
                                @php $subject_grades = $subject->grades; @endphp
                                @foreach ($subject_grades as $item)
                                <template x-if="state_id == '{{$subject->state_id}}' && '{{$item['id']}}' == student1.grade_id">
                                    <option value="{{$subject->name}}">{{$subject->name}}</option>
                                </template>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="main_result" class="custom_label">What is the main result you are looking for? <span style="color: red;">*</span></label>
                            <select name="main_result" id="main_result" class="form-control" wire:model="student1.main_result" x-model="student1.main_result">
                                <option value=""></option>
                                <option value="Get better results at school">Get better results at school</option>
                                <option value="Boost confidence">Boost confidence</option>
                                <option value="Enjoy the learning experience">Enjoy the learning experience</option>
                                <option value="A mix of all">A mix of all</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="one_student_notes" class="custom_label">How can we help?</label>
                            <p class="sub_label">Please provide as much info about your child as you can - why you are seeking a tutor, if and what struggles they have at school and any other details that may be relevant in matching them up with the right tutor.</p>
                            <textarea name="one_student_notes" class="form-control" id="one_student_notes" cols="30" rows="10" wire:model="student1.notes" x-model="student1.notes"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <p>We would love to know more about the student so we can match them with the perfect tutor for them:</p>
                            <label class="custom_label">How would you describe their current performance at school?</label>
                            <div class="radio-toolbar">
                                <input type="radio" class="d-none" id="radio_struggling" name="student_performance" value="Struggling at school" wire:model="student1.student_performance" x-model="student1.student_performance">
                                <label for="radio_struggling">Struggling at school</label>

                                <input type="radio" class="d-none" id="radio_excelling" name="student_performance" value="Excelling at school" wire:model="student1.student_performance" x-model="student1.student_performance">
                                <label for="radio_excelling">Excelling at school</label>

                                <input type="radio" class="d-none" id="radio_performance_somewhere" name="student_performance" value="Somewhere in between" wire:model="student1.student_performance" x-model="student1.student_performance">
                                <label for="radio_performance_somewhere">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="student_attitude" class="custom_label">How would you describe their attitude towards school?</label>
                            <div class="radio-toolbar">
                                <input type="radio" id="radio_love" class="d-none" name="student_attitude" value="They love school" wire:model="student1.student_attitude" x-model="student1.student_attitude">
                                <label for="radio_love">They love school</label>

                                <input type="radio" id="radio_dislike" class="d-none" name="student_attitude" value="They dislike school" wire:model="student1.student_attitude" x-model="student1.student_attitude">
                                <label for="radio_dislike">They dislike school</label>

                                <input type="radio" id="radio_attitude_somewhere" class="d-none" name="student_attitude" value="Somewhere in between" wire:model="student1.student_attitude" x-model="student1.student_attitude">
                                <label for="radio_attitude_somewhere">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="student_mind" class="custom_label">How would you describe their mind?</label>
                            <div class="radio-toolbar">
                                <input type="radio" id="radio_creative" class="d-none" name="student_mind" value="Creative">
                                <label for="radio_creative" wire:model="student1.student_mind" x-model="student1.student_mind">Creative</label>

                                <input type="radio" id="radio_analytical" class="d-none" name="student_mind" value="Analytical" wire:model="student1.student_mind" x-model="student1.student_mind">
                                <label for="radio_analytical">Analytical</label>

                                <input type="radio" id="radio_mind_somewhere" class="d-none" name="student_mind" value="Somewhere in between" wire:model="student1.student_mind" x-model="student1.student_mind">
                                <label for="radio_mind_somewhere">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="student_personality" class="custom_label">How would you describe their personality?</label>
                            <div class="radio-toolbar">
                                <input type="radio" id="radio_shy" class="d-none" name="student_personality" value="Shy" wire:model="student1.student_personality" x-model="student1.student_personality">
                                <label for="radio_shy">Shy</label>

                                <input type="radio" id="radio_outgoing" class="d-none" name="student_personality" value="Outgoing" wire:model="student1.student_personality" x-model="student1.student_personality">
                                <label for="radio_outgoing">Outgoing</label>

                                <input type="radio" id="radio_personality_somewhere" class="d-none" name="student_personality" value="Somewhere in between" wire:model="student1.student_personality" x-model="student1.student_personality">
                                <label for="radio_personality_somewhere">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="favourite_1" class="custom_label">What are their 3 favourite things to do (ie. video games, soccer, swimming)</label>
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="text" name="favourite_1" class="form-control" placeholder="1." id="favourite_1" wire:model="student1.favourite_1" x-model="student1.favourite_1">
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="text" name="favourite_2" class="form-control" placeholder="2." id="favourite_2" wire:model="student1.favourite_2" x-model="student1.favourite_2">
                        </div>
                        <div class="col-md-4 mb-4 mb-md-5">
                            <input type="text" name="favourite_3" class="form-control" placeholder="3." id="favourite_3" wire:model="student1.favourite_3" x-model="student1.favourite_3">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <button type="button" class="btn bg-light" x-on:click="goStep(3)">Previous</button>
                            <button type="button" class="btn bg-custom-warning text-white" x-on:click="toStep(5)">Next</button>
                        </div>
                    </div>
                </form>
            </div>
        </template>
        <template x-if="step==5">
            <form action="#" id="student_details_one_2">
                <div class="row">
                    <div class="col-12">
                        <h3>First student availabilities:</h3>
                        <p>Please select all the days and times that could work for lessons. The more options you provide, the faster we will be able to organise the right tutor for you!</p>
                        <div class="availabilities_wrapper mb-4 px-3">
                            <div class="row">
                                @php $i = 0; @endphp
                                @foreach ($total_availabilities as $item)
                                <div class="col-md text-center" data-day="{{$item->name}}">
                                    <h5>{{$item->name}}</h5>
                                    @forelse ($item->getAvailabilitiesName() as $ele)
                                    @php
                                    $avail_hour = $item->short_name . '-' . $ele;
                                    $i++;
                                    @endphp
                                    <div class="avail_hours p-0 w-100">
                                        <input type="checkbox" class="d-none" id="availability-{{$i}}" value="{{$avail_hour}}" wire:model="student1.availabilities" x-model="student1.availabilities" />
                                        <label for="availability-{{$i}}" class="text-center py-1 w-100">{{$ele}}</label>
                                    </div>
                                    @empty
                                    <div>Not exist</div>
                                    @endforelse
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="when_start_one" class="custom_label">When would you like to start? <span style="color: red;">*</span></label>
                            <select name="when_start_one" id="when_start_one" class="form-control" wire:model="student1.start_date" x-model="student1.start_date">
                                <option value=""></option>
                                <option value="ASAP">ASAP</option>
                                <option value="At a later date">At a later date</option>
                            </select>
                        </div>
                        <div class="mb-4" x-show="student1.start_date == 'At a later date'" id="container_start_date_one">
                            <label for="start_date_one" class="custom_label">When is the soonest you could begin?</label>
                            <input type="text" name="start_date_one" class="form-control start_date" id="start_date_one" wire:model="student1.start_date_picker" x-model="student1.start_date_picker">
                        </div>
                    </div>
                    <div class="col-md-12 mb-5">
                        <button type="button" class="btn bg-light" x-on:click="goStep(4)">Previous</button>
                        <button type="button" class="btn bg-custom-warning text-white" x-on:click="toStep(6)">Next</button>
                    </div>
                </div>
            </form>
        </template>
        <template x-if="step == 6">
            <div>
                <form action="#" id="student_details_two_1">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h3 class="text-custom-warning">Second student details:</h3>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="second_first_name" class="custom_label">Second Student first name: <span style="color: red;">*</span></label>
                            <input type="text" name="second_first_name" class="form-control" id="second_first_name" wire:model="student2.student_first_name" x-model="student2.student_first_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="second_last_name" class="custom_label">Second Student last name: <span style="color: red;">*</span></label>
                            <input type="text" name="second_last_name" class="form-control" id="second_last_name" wire:model="student2.student_last_name" x-model="student2.student_last_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="second_school" class="custom_label">What school do they go to?</label>
                            <input type="text" name="second_school" class="form-control" id="second_school" wire:model="student2.student_school" x-model="student2.student_school">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="second_grade_id" class="custom_label">What grade are they in? <span style="color: red;">*</span></label>
                            <select name="second_grade_id" id="second_grade_id" class="form-control" wire:model="student2.grade_id" x-model="student2.grade_id">
                                <option value=""></option>
                                @foreach ($grades as $grade)
                                <option value="{{$grade->id}}">{{$grade->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="student2_subject" class="custom_label">What subject do they need support in? <span style="color: red;">*</span></label>
                            <p class="sub_label">Please select the subject that best describes their area of need.</p>
                            <select name="student2_subject" id="student2_subject" class="form-control" wire:model="student2.subject" x-model="student2.subject">
                                <option value=""></option>
                                @foreach ($all_subjects as $subject)
                                @php $subject_grades = $subject->grades; @endphp
                                @foreach ($subject_grades as $item)
                                <template x-if="state_id == '{{$subject->state_id}}' && '{{$item['id']}}' == student2.grade_id">
                                    <option value="{{$subject->name}}">{{$subject->name}}</option>
                                </template>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="two_main_result" class="custom_label">What is the main result you are looking for? <span style="color: red;">*</span></label>
                            <select name="two_main_result" id="two_main_result" class="form-control" wire:model="student2.main_result" x-model="student2.main_result">
                                <option value=""></option>
                                <option value="Get better results at school">Get better results at school</option>
                                <option value="Boost confidence">Boost confidence</option>
                                <option value="Enjoy the learning experience">Enjoy the learning experience</option>
                                <option value="A mix of all">A mix of all</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="two_student_notes" class="custom_label">How can we help?</label>
                            <p class="sub_label">Please provide as much info about your child as you can - why you are seeking a tutor, if and what struggles they have at school and any other details that may be relevant in matching them up with the right tutor.</p>
                            <textarea name="two_student_notes" class="form-control" id="two_student_notes" cols="30" rows="10" wire:model="student2.notes" x-model="student2.notes"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <p>We would love to know more about the student so we can match them with the perfect tutor for them:</p>
                            <label class="custom_label">How would you describe their current performance at school?</label>
                            <div class="radio-toolbar">
                                <input type="radio" class="d-none" id="radio_struggling_two" name="two_student_performance" value="Struggling at school" wire:model="student2.student_performance" x-model="student2.student_performance">
                                <label for="radio_struggling_two">Struggling at school</label>

                                <input type="radio" class="d-none" id="radio_excelling_two" name="two_student_performance" value="Excelling at school" wire:model="student2.student_performance" x-model="student2.student_performance">
                                <label for="radio_excelling_two">Excelling at school</label>

                                <input type="radio" class="d-none" id="radio_performance_somewhere_two" name="two_student_performance" value="Somewhere in between" wire:model="student2.student_performance" x-model="student2.student_performance">
                                <label for="radio_performance_somewhere_two">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="custom_label">How would you describe their attitude towards school?</label>
                            <div class="radio-toolbar">
                                <input type="radio" id="radio_love_two" class="d-none" name="two_student_attitude" value="They love school" wire:model="student2.student_attitude" x-model="student2.student_attitude">
                                <label for="radio_love_two">They love school</label>

                                <input type="radio" id="radio_dislike_two" class="d-none" name="two_student_attitude" value="They dislike school" wire:model="student2.student_attitude" x-model="student2.student_attitude">
                                <label for="radio_dislike_two">They dislike school</label>

                                <input type="radio" id="radio_attitude_somewhere_two" class="d-none" name="two_student_attitude" value="Somewhere in between" wire:model="student2.student_attitude" x-model="student2.student_attitude">
                                <label for="radio_attitude_somewhere_two">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="custom_label">How would you describe their mind?</label>
                            <div class="radio-toolbar">
                                <input type="radio" id="radio_creative_two" class="d-none" name="two_student_mind" value="Creative">
                                <label for="radio_creative_two" wire:model="student2.student_mind" x-model="student2.student_mind">Creative</label>

                                <input type="radio" id="radio_analytical_two" class="d-none" name="two_student_mind" value="Analytical" wire:model="student2.student_mind" x-model="student2.student_mind">
                                <label for="radio_analytical_two">Analytical</label>

                                <input type="radio" id="radio_mind_somewhere_two" class="d-none" name="two_student_mind" value="Somewhere in between" wire:model="student2.student_mind" x-model="student2.student_mind">
                                <label for="radio_mind_somewhere_two">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="custom_label">How would you describe their personality?</label>
                            <div class="radio-toolbar">
                                <input type="radio" id="radio_shy_two" class="d-none" name="two_student_personality" value="Shy" wire:model="student2.student_personality" x-model="student2.student_personality">
                                <label for="radio_shy_two">Shy</label>

                                <input type="radio" id="radio_outgoing_two" class="d-none" name="two_student_personality" value="Outgoing" wire:model="student2.student_personality" x-model="student2.student_personality">
                                <label for="radio_outgoing_two">Outgoing</label>

                                <input type="radio" id="radio_personality_somewhere_two" class="d-none" name="two_student_personality" value="Somewhere in between" wire:model="student2.student_personality" x-model="student2.student_personality">
                                <label for="radio_personality_somewhere_two">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="two_favourite_1" class="custom_label">What are their 3 favourite things to do (ie. video games, soccer, swimming)</label>
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="text" name="two_favourite_1" class="form-control" placeholder="1." id="two_favourite_1" wire:model="student2.favourite_1" x-model="student2.favourite_1">
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="text" name="two_favourite_2" class="form-control" placeholder="2." id="tow_favourite_2" wire:model="student2.favourite_2" x-model="student2.favourite_2">
                        </div>
                        <div class="col-md-4 mb-4 mb-md-5">
                            <input type="text" name="two_favourite_3" class="form-control" placeholder="3." id="tow_favourite_3" wire:model="student2.favourite_3" x-model="student2.favourite_3">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <button type="button" class="btn bg-light" x-on:click="goStep(5)">Previous</button>
                            <button type="button" class="btn bg-custom-warning text-white" x-on:click="toStep(7)">Next</button>
                        </div>
                    </div>
                </form>
            </div>
        </template>
        <template x-if="step==7">
            <form action="#" id="student_details_two_2">
                <div class="row">
                    <div class="col-12">
                        <h3>Second student availabilities:</h3>
                        <p>Please select all the days and times that could work for lessons. The more options you provide, the faster we will be able to organise the right tutor for you!</p>
                        <div class="availabilities_wrapper mb-4 px-3">
                            <div class="row">
                                @php $i = 0; @endphp
                                @foreach ($total_availabilities as $item)
                                <div class="col-md text-center" data-day="{{$item->name}}">
                                    <h5>{{$item->name}}</h5>
                                    @forelse ($item->getAvailabilitiesName() as $ele)
                                    @php
                                    $avail_hour = $item->short_name . '-' . $ele;
                                    $i++;
                                    @endphp
                                    <div class="avail_hours p-0 w-100">
                                        <input type="checkbox" class="d-none" id="availability-{{$i}}" value="{{$avail_hour}}" wire:model="student2.availabilities" x-model="student2.availabilities" />
                                        <label for="availability-{{$i}}" class="text-center py-1 w-100">{{$ele}}</label>
                                    </div>
                                    @empty
                                    <div>Not exist</div>
                                    @endforelse
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="when_start_two" class="custom_label">When would you like to start? <span style="color: red;">*</span></label>
                            <select name="when_start_two" id="when_start_two" class="form-control" wire:model="student2.start_date" x-model="student2.start_date">
                                <option value=""></option>
                                <option value="ASAP">ASAP</option>
                                <option value="At a later date">At a later date</option>
                            </select>
                        </div>
                        <div class="mb-4" x-show="student2.start_date == 'At a later date'" id="container_start_date_two">
                            <label for="start_date_two" class="custom_label">When is the soonest you could begin?</label>
                            <input type="text" name="start_date_two" class="form-control start_date" id="start_date_two" wire:model="student2.start_date_picker" x-model="student2.start_date_picker">
                        </div>
                    </div>
                    <div class="col-md-12 mb-5">
                        <button type="button" class="btn bg-light" x-on:click="goStep(6)">Previous</button>
                        <button type="button" class="btn bg-custom-warning text-white" x-on:click="toStep(8)">Next</button>
                    </div>
                </div>
            </form>
        </template>
        <template x-if="step == 8">
            <div>
                <form action="#" id="student_details_three_1">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h3 class="text-custom-warning">Third student details:</h3>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="third_first_name" class="custom_label">Three Student first name: <span style="color: red;">*</span></label>
                            <input type="text" name="third_first_name" class="form-control" id="third_first_name" wire:model="student3.student_first_name" x-model="student3.student_first_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="third_last_name" class="custom_label">Three Student last name: <span style="color: red;">*</span></label>
                            <input type="text" name="third_last_name" class="form-control" id="third_last_name" wire:model="student3.student_last_name" x-model="student3.student_last_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="third_school" class="custom_label">What school do they go to?</label>
                            <input type="text" name="third_school" class="form-control" id="third_school" wire:model="student3.student_school" x-model="student3.student_school">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="third_grade_id" class="custom_label">What grade are they in? <span style="color: red;">*</span></label>
                            <select name="third_grade_id" id="third_grade_id" class="form-control" wire:model="student3.grade_id" x-model="student3.grade_id">
                                <option value=""></option>
                                @foreach ($grades as $grade)
                                <option value="{{$grade->id}}">{{$grade->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="student3_subject" class="custom_label">What subject do they need support in? <span style="color: red;">*</span></label>
                            <p class="sub_label">Please select the subject that best describes their area of need.</p>
                            <select name="student3_subject" id="student3_subject" class="form-control" wire:model="student3.subject" x-model="student3.subject">
                                <option value=""></option>
                                @foreach ($all_subjects as $subject)
                                @php $subject_grades = $subject->grades; @endphp
                                @foreach ($subject_grades as $item)
                                <template x-if="state_id == '{{$subject->state_id}}' && '{{$item['id']}}' == student3.grade_id">
                                    <option value="{{$subject->name}}">{{$subject->name}}</option>
                                </template>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="third_main_result" class="custom_label">What is the main result you are looking for? <span style="color: red;">*</span></label>
                            <select name="third_main_result" id="third_main_result" class="form-control" wire:model="student3.main_result" x-model="student3.main_result">
                                <option value=""></option>
                                <option value="Get better results at school">Get better results at school</option>
                                <option value="Boost confidence">Boost confidence</option>
                                <option value="Enjoy the learning experience">Enjoy the learning experience</option>
                                <option value="A mix of all">A mix of all</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="three_student_notes" class="custom_label">How can we help?</label>
                            <p class="sub_label">Please provide as much info about your child as you can - why you are seeking a tutor, if and what struggles they have at school and any other details that may be relevant in matching them up with the right tutor.</p>
                            <textarea name="three_student_notes" class="form-control" id="three_student_notes" cols="30" rows="10" wire:model="student3.notes" x-model="student3.notes"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <p>We would love to know more about the student so we can match them with the perfect tutor for them:</p>
                            <label class="custom_label">How would you describe their current performance at school?</label>
                            <div class="radio-toolbar">
                                <input type="radio" class="d-none" id="radio_struggling_third" name="third_student_performance" value="Struggling at school" wire:model="student3.student_performance" x-model="student3.student_performance">
                                <label for="radio_struggling_third">Struggling at school</label>

                                <input type="radio" class="d-none" id="radio_excelling_third" name="third_student_performance" value="Excelling at school" wire:model="student3.student_performance" x-model="student3.student_performance">
                                <label for="radio_excelling_third">Excelling at school</label>

                                <input type="radio" class="d-none" id="radio_performance_somewhere_third" name="third_student_performance" value="Somewhere in between" wire:model="student3.student_performance" x-model="student3.student_performance">
                                <label for="radio_performance_somewhere_third">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="custom_label">How would you describe their attitude towards school?</label>
                            <div class="radio-toolbar">
                                <input type="radio" id="radio_love_third" class="d-none" name="third_student_attitude" value="They love school" wire:model="student3.student_attitude" x-model="student3.student_attitude">
                                <label for="radio_love_third">They love school</label>

                                <input type="radio" id="radio_dislike_third" class="d-none" name="third_student_attitude" value="They dislike school" wire:model="student3.student_attitude" x-model="student3.student_attitude">
                                <label for="radio_dislike_third">They dislike school</label>

                                <input type="radio" id="radio_attitude_somewhere_third" class="d-none" name="third_student_attitude" value="Somewhere in between" wire:model="student3.student_attitude" x-model="student3.student_attitude">
                                <label for="radio_attitude_somewhere_third">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="custom_label">How would you describe their mind?</label>
                            <div class="radio-toolbar">
                                <input type="radio" id="radio_creative_third" class="d-none" name="third_student_mind" value="Creative">
                                <label for="radio_creative_third" wire:model="student3.student_mind" x-model="student3.student_mind">Creative</label>

                                <input type="radio" id="radio_analytical_third" class="d-none" name="third_student_mind" value="Analytical" wire:model="student3.student_mind" x-model="student3.student_mind">
                                <label for="radio_analytical_third">Analytical</label>

                                <input type="radio" id="radio_mind_somewhere_third" class="d-none" name="third_student_mind" value="Somewhere in between" wire:model="student3.student_mind" x-model="student3.student_mind">
                                <label for="radio_mind_somewhere_third">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="custom_label">How would you describe their personality?</label>
                            <div class="radio-toolbar">
                                <input type="radio" id="radio_shy_third" class="d-none" name="third_student_personality" value="Shy" wire:model="student3.student_personality" x-model="student3.student_personality">
                                <label for="radio_shy_third">Shy</label>

                                <input type="radio" id="radio_outgoing_third" class="d-none" name="third_student_personality" value="Outgoing" wire:model="student3.student_personality" x-model="student3.student_personality">
                                <label for="radio_outgoing_third">Outgoing</label>

                                <input type="radio" id="radio_personality_somewhere_third" class="d-none" name="third_student_personality" value="Somewhere in between" wire:model="student3.student_personality" x-model="student3.student_personality">
                                <label for="radio_personality_somewhere_third">Somewhere in between</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="third_favourite_1" class="custom_label">What are their 3 favourite things to do (ie. video games, soccer, swimming)</label>
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="text" name="third_favourite_1" class="form-control" placeholder="1." id="third_favourite_1" wire:model="student3.favourite_1" x-model="student3.favourite_1">
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="text" name="third_favourite_2" class="form-control" placeholder="2." id="third_favourite_3" wire:model="student3.favourite_2" x-model="student3.favourite_2">
                        </div>
                        <div class="col-md-4 mb-4 mb-md-5">
                            <input type="text" name="third_favourite_3" class="form-control" placeholder="3." id="third_favourite_3" wire:model="student3.favourite_3" x-model="student3.favourite_3">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <button type="button" class="btn bg-light" x-on:click="goStep(7)">Previous</button>
                            <button type="button" class="btn bg-custom-warning text-white" x-on:click="toStep(9)">Next</button>
                        </div>
                    </div>
                </form>
            </div>
        </template>
        <template x-if="step==9">
            <form action="#" id="student_details_three_2">
                <div class="row">
                    <div class="col-12">
                        <h3>Third student availabilities:</h3>
                        <p>Please select all the days and times that could work for lessons. The more options you provide, the faster we will be able to organise the right tutor for you!</p>
                        <div class="availabilities_wrapper mb-4 px-3">
                            <div class="row">
                                @php $i = 0; @endphp
                                @foreach ($total_availabilities as $item)
                                <div class="col-md text-center" data-day="{{$item->name}}">
                                    <h5>{{$item->name}}</h5>
                                    @forelse ($item->getAvailabilitiesName() as $ele)
                                    @php
                                    $avail_hour = $item->short_name . '-' . $ele;
                                    $i++;
                                    @endphp
                                    <div class="avail_hours p-0 w-100">
                                        <input type="checkbox" class="d-none" id="availability-{{$i}}" value="{{$avail_hour}}" wire:model="student3.availabilities" x-model="student3.availabilities" />
                                        <label for="availability-{{$i}}" class="text-center py-1 w-100">{{$ele}}</label>
                                    </div>
                                    @empty
                                    <div>Not exist</div>
                                    @endforelse
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="when_start_three" class="custom_label">When would you like to start? <span style="color: red;">*</span></label>
                            <select name="when_start_three" id="when_start_three" class="form-control" wire:model="student3.start_date" x-model="student3.start_date">
                                <option value=""></option>
                                <option value="ASAP">ASAP</option>
                                <option value="At a later date">At a later date</option>
                            </select>
                        </div>
                        <div class="mb-4" x-show="student3.start_date == 'At a later date'" id="container_start_date_three">
                            <label for="start_date_three" class="custom_label">When is the soonest you could begin?</label>
                            <input type="text" name="start_date_three" class="form-control start_date" id="start_date_three" wire:model="student3.start_date_picker" x-model="student3.start_date_picker">
                        </div>
                    </div>
                    <div class="col-md-12 mb-5">
                        <button type="button" class="btn bg-light" x-on:click="goStep(8)">Previous</button>
                        <button type="button" class="btn bg-custom-warning text-white" x-on:click="toStep(10)">Next</button>
                    </div>
                </div>
            </form>
        </template>
        <template x-if="step == 10">
            <form action="#" id="lesson_location">
                <div class="row">
                    <div class="col-12">
                        <div class="parent_desc mb-3 mb-md-4">
                            <h2>Location of lessons</h2>
                            <p class="mt-3">We give you the flexibility of face-to-face lessons in your home when they suit you; no battling traffic or fighting for parking. Your tutor can also meet online in our custom-built online classroom from anywhere, anytime!</p>
                        </div>
                        <p><strong>How would you like to have the lessons? <span style="color: red;">*</span></strong></p>
                        <div class="form-check mb-2">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="face_to_face" name="session_type_id" value="1" wire:model="session_type_id" x-model="session_type_id">Face to face
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="online" name="session_type_id" value="2" wire:model="session_type_id" x-model="session_type_id">Online
                            </label>
                        </div>
                        <div class="address mt-3" x-show="session_type_id == 1">
                            <label for="address" class="custom_label">What is your address? <span style="color: red;">*</span></label>
                            <input type="text" name="address" class="form-control" id="address" placeholder="Enter a location" wire:model="address" x-model="address">
                        </div>
                        <div class="online_desc mt-3" x-show="session_type_id == 2">
                            <p>Awesome! Once we have lined up the perfect tutor for your child, we will send you a unique URL for our online platform – you will be able to connect with your tutor in our online one-on-one classroom from any device!</p>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 my-5">
                        <button type="button" class="btn bg-light" x-on:click="goStep(9)">Previous</button>
                        <button type="button" :disabled="session_type_id == ''" class="btn bg-custom-warning text-white" x-on:click="toStep(11)">Next</button>
                    </div>
                </div>
            </form>
        </template>
        <template x-if="step == 11">
            <div class="row mb-5 text-center">
                <div class="col-12">
                    <h2 style="font-weight: 700;">Our commitment to you:</h2>
                    <p class="mt-3 custom_color">We know this is the start of something amazing for both you and your child.</p>
                    <br>
                    <p>Greater confidence, focus, enthusiasm – and of course, academic success, are all just around the corner.</p>
                    <br>
                    <p>We are 100% committed to bringing out the best in your child and helping them perform at their full potential.</p>
                    <br>
                    <p>Once you hit the button below we will review your details and begin working with our team of incredible tutors to pair up the perfect mentor for your child.</p>
                    <br>
                    <p>We know that one day you’ll look back on this moment right here and be so glad you made this choice.</p>
                    <br>
                    <div class="text-center mb-4 mb-md-5">
                        <button x-show="!loading" type="button" class="btn bg-custom-warning text-white" x-on:click="submitParentBooking">Let's do this</button>
                        <button x-show="loading" class="btn bg-custom-warning text-white" type="button" disabled>
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            loading...
                        </button>
                    </div>
                    <div x-show="result == false" class="custom_style final_error mt-3" style="background-color: #feeef6;">
                        <p class="m-0">Something went wrong! Please try it in a bit later again!</p>
                    </div>
                </div>
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
            step: 1,
            who_you_are: '',
            parent_first_name: '',
            parent_last_name: '',
            parent_phone: '',
            parent_email: '',
            postcode: '',
            state_id: '',
            referral: '',
            session_type_id: '',
            address: '',
            students: 1,
            loading: false,
            result: true,
            student1: {
                'student_first_name': '',
                'student_last_name': '',
                'grade_id': '',
                'student_school': '',
                'start_date': '',
                'start_date_picker': '',
                'subject': '',
                'availabilities': [],
                'main_result': '',
                'student_performance': '',
                'student_attitude': '',
                'student_mind': '',
                'student_personality': '',
                'student_favourite': '',
                'notes': '',
                'favourite_1': '',
                'favourite_2': '',
                'favourite_3': '',
            },
            student2: {
                'student_first_name': '',
                'student_last_name': '',
                'grade_id': '',
                'student_school': '',
                'start_date': '',
                'start_date_picker': '',
                'subject': '',
                'availabilities': [],
                'main_result': '',
                'student_performance': '',
                'student_attitude': '',
                'student_mind': '',
                'student_personality': '',
                'student_favourite': '',
                'notes': '',
                'favourite_1': '',
                'favourite_2': '',
                'favourite_3': '',
            },
            student3: {
                'student_first_name': '',
                'student_last_name': '',
                'grade_id': '',
                'student_school': '',
                'start_date': '',
                'start_date_picker': '',
                'subject': '',
                'availabilities': [],
                'main_result': '',
                'student_performance': '',
                'student_attitude': '',
                'student_mind': '',
                'student_personality': '',
                'student_favourite': '',
                'notes': '',
                'favourite_1': '',
                'favourite_2': '',
                'favourite_3': '',
            },
            goStep(step) {
                this.step = step;
                let temp = this;
                if (step == 5) {
                    setTimeout(() => {
                        $('#start_date_one').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            startDate: new Date(),
                            todayHighlight: true,
                        }).on('changeDate', function(e) {
                            var selectedDate = e.format(0, 'dd/mm/yyyy');
                            @this.set('student1.start_date_picker', selectedDate);
                            temp.student1.start_date_picker = selectedDate;
                        });
                    }, 100)
                }
                if (step == 7) {
                    setTimeout(() => {
                        $('#start_date_two').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            startDate: new Date(),
                            todayHighlight: true,
                        }).on('changeDate', function(e) {
                            var selectedDate = e.format(0, 'dd/mm/yyyy');
                            @this.set('student2.start_date_picker', selectedDate);
                            temp.student2.start_date_picker = selectedDate;
                        });
                    }, 100)
                }
                if (step == 9) {
                    setTimeout(() => {
                        $('#start_date_three').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            startDate: new Date(),
                            todayHighlight: true,
                        }).on('changeDate', function(e) {
                            var selectedDate = e.format(0, 'dd/mm/yyyy');
                            @this.set('student3.start_date_picker', selectedDate);
                            temp.student3.start_date_picker = selectedDate;
                        });
                    }, 100)
                }
                if (step == 10) {
                    setTimeout(() => {
                        var options = {
                            componentRestrictions: {
                                country: "au"
                            }
                        };
                        var input = document.getElementById('address');
                        new google.maps.places.Autocomplete(input, options);
                    }, 100);
                }
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
                    case 5:
                        $('#student_details_one_1').validate({
                            rules: {
                                first_name: 'required',
                                last_name: 'required',
                                grade_id: 'required',
                                student1_subject: 'required',
                            }
                        })
                        if ($('#student_details_one_1').valid()) {
                            window.scrollTo(0, 0);
                            this.goStep(5);
                        }
                        break;
                    case 6:
                        if (this.student1.availabilities.length == 0) {
                            toastr.error('Please select availabilities.');
                            return;
                        }
                        $('#student_details_one_2').validate({
                            rules: {
                                when_start_one: 'required',
                            }
                        })
                        if (!$('#student_details_one_2').valid()) return;
                        if (this.student1.start_date == 'At a later date' && this.student1.start_date_picker == '') {
                            toastr.error('Please select start date.');
                            return;
                        }
                        window.scrollTo(0, 0);
                        if (this.students > 1) this.goStep(6);
                        else this.goStep(10);
                        break;
                    case 7:
                        $('#student_details_two_1').validate({
                            rules: {
                                second_first_name: 'required',
                                second_last_name: 'required',
                                second_grade_id: 'required',
                                student2_subject: 'required',
                            }
                        })
                        if ($('#student_details_two_1').valid()) {
                            window.scrollTo(0, 0);
                            this.goStep(7);
                        }
                        break;
                    case 8:
                        if (this.student2.availabilities.length == 0) {
                            toastr.error('Please select availabilities.');
                            return;
                        }
                        $('#student_details_two_2').validate({
                            rules: {
                                when_start_two: 'required',
                            }
                        })
                        if (!$('#student_details_two_2').valid()) return;
                        if (this.student2.start_date == 'At a later date' && this.student2.start_date_picker == '') {
                            toastr.error('Please select start date.');
                            return;
                        }
                        window.scrollTo(0, 0);
                        if (this.students > 2) this.goStep(8);
                        else this.goStep(10);
                        break;
                    case 9:
                        $('#student_details_three_1').validate({
                            rules: {
                                third_first_name: 'required',
                                third_last_name: 'required',
                                third_grade_id: 'required',
                                student3_subject: 'required',
                            }
                        })
                        if ($('#student_details_three_1').valid()) {
                            window.scrollTo(0, 0);
                            this.goStep(9);
                        }
                        break;
                    case 10:
                        if (this.student3.availabilities.length == 0) {
                            toastr.error('Please select availabilities.');
                            return;
                        }
                        $('#student_details_three_2').validate({
                            rules: {
                                when_start_three: 'required',
                            }
                        })
                        if (!$('#student_details_three_2').valid()) return;
                        if (this.student3.start_date == 'At a later date' && this.student3.start_date_picker == '') {
                            toastr.error('Please select start date.');
                            return;
                        }
                        window.scrollTo(0, 0);
                        this.goStep(10);
                        break;
                    case 11:
                        $('#lesson_location').validate({
                            rules: {
                                address: 'required',
                            }
                        });
                        if ($('#lesson_location').valid()) {
                            window.scrollTo(0, 0);
                            this.goStep(11);
                        }
                        break;
                    default:
                }
            },
            async submitParentBooking() {
                this.loading = true;
                let result = await @this.call('submitParentBooking', $('#address').val());
                this.loading = false;
                this.result = result;
            }
        }))
    })
</script>