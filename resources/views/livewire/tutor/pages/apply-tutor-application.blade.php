<div x-data="init">
    @section('title')
    Apply to work with us // Alchemy Tuition
    @endsection
    @section('meta')
    Think you'd make a great tutor? We would love to get to know you! Our application process is quick and easy. Start your application now!
    @endsection

    <div class="container text-black py-2 py-md-5">
        <p class="mb-2 mt-md-4">Step <span x-text="step"></span> of 8</p>
        <div class="progress mb-4">
            <div class="progress-bar bg-warning" x-ref="progress_step"></div>
        </div>
        <template id="step-1" x-if="step == 1">
            <div>
                <div class="row text-center mb-4">
                    <div class="col-md-12">
                        <h2 class="mb-3">Apply to become an Alchemy tutor</h2>
                        <p>Our application process is easy:</p>
                    </div>
                </div>
                <div class="row text-center mb-4">
                    <div class="col-md-4">
                        <h3>Apply</h3>
                        <p>Complete our application form. You might notice we ask some unusual questions! Unlike most jobs, we care more about your personality than your experience, so let it shine through! You'll also need to provide <a href="/character-references" target="_blank">2 character references</a>, so grab their email addresses now.</p>
                    </div>
                    <div class="col-md-4">
                        <h3>Interview</h3>
                        <p>If we think you've got what it takes to join our team we will email you to line up a 10 minute online video interview. We want to see how you communicate and learn more about your dreams and goals. You can also ask any questions you have about the role!</p>
                    </div>
                    <div class="col-md-4">
                        <h3>Accept</h3>
                        <p>We'll send you a job offer and invite you to register into our platform. You'll go through your onboarding - a detailed walkthrough of how we work, how you get paid and how to take on new students. From there we hustle to get you paired up with your first student!</p>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-light py-2" x-on:click="goStep(2)">Get Started</button>
                    </div>
                </div>
            </div>
        </template>
        <template id="step-2" x-if="step == 2">
            <form action="#" id="tutor_contact">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_first_name" class="custom_label">Your first name <span class="text-danger">*</span></label>
                        <div class="custom_input">
                            <input type="text" name="tutor_application_first_name" class="form-control" id="tutor_application_first_name" wire:model="tutor_application_first_name" x-model="tutor_application_first_name">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_last_name" class="custom_label">Your last name <span class="text-danger">*</span></label>
                        <div class="custom_input">
                            <input type="text" name="tutor_application_last_name" class="form-control" id="tutor_application_last_name" wire:model="tutor_application_last_name" x-model="tutor_application_last_name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_email_address" class="custom_label">Your email address <span class="text-danger">*</span></label>
                        <input type="email" name="tutor_application_email_address" class="form-control" id="tutor_application_email_address" wire:model="tutor_application_email_address" x-model="tutor_application_email_address">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_phone_number" class="custom_label">Your phone number <span class="text-danger">*</span></label>
                        <input type="text" name="tutor_application_phone_number" class="form-control" id="tutor_application_phone_number" wire:model="tutor_application_phone_number" x-model="tutor_application_phone_number">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tutor_application_state" class="custom_label">What state are you in? <span class="text-danger">*</span></label>
                        <select name="tutor_application_state" id="tutor_application_state" class="form-control" wire:model="tutor_application_state" x-model="tutor_application_state">
                            <option value=""></option>
                            @forelse ($states as $state)
                            <option value="{{$state->name}}">{{$state->name}}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tutor_application_postal" class="custom_label">What is your postcode? <span class="text-danger">*</span></label>
                        <input type="text" name="tutor_application_postal" class="form-control" id="tutor_application_postal" wire:model="tutor_application_postal" x-model="tutor_application_postal">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tutor_application_suburb" class="custom_label">What suburb do you live in?</label>
                        <input type="text" name="tutor_application_suburb" class="form-control" id="tutor_application_suburb" wire:model="tutor_application_suburb" x-model="tutor_application_suburb">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_source" class="custom_label">How did you hear about us?</label>
                        <select name="tutor_application_source" id="tutor_application_source" class="form-control" wire:model="tutor_application_source" x-model="tutor_application_source">
                            <option value=""></option>
                            <option value="Referred by a friend">Referred by a friend</option>
                            <option value="A job ad on Indeed">A job ad on Indeed</option>
                            <option value="A job ad on seek">A job ad on seek</option>
                            <option value="A post on Facebook or Instagram">A post on Facebook or Instagram</option>
                            <option value="A post on Gumtree">A post on Gumtree</option>
                            <option value="A Google search">A Google search</option>
                            <option value="A poster or flyer">A poster or flyer</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_referral_code" class="custom_label">If you have a referral code, please enter it here</label>
                        <input type="text" name="tutor_application_referral_code" class="form-control" value="" id="tutor_application_referral_code" wire:model="tutor_application_referral_code" x-model="tutor_application_referral_code">
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <input type="button" id="prev_page_1" class="btn btn-light py-2" value="Previous" x-on:click="goStep(1)">
                        <input type="button" id="to_page_3" class="btn btn-light py-2" value="Next" x-on:click="toStep3">
                    </div>
                </div>
            </form>
        </template>

        <template id="step-3" x-if="step == 3">
            <form action="#" id="tutor_education">
                <div class="row mb-3">
                    <div class="col-12">
                        <h2>Your education:</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_graduated_year" class="custom_label">What year did you graduate from High School?</label>
                        <select name="tutor_application_graduated_year" id="tutor_application_graduated_year" class="form-control" wire:model="tutor_application_graduated_year" x-model="tutor_application_graduated_year">
                            @for ($i = 0; $i < 100; $i++) <option value="{{date('Y') - $i}}">{{date('Y') - $i}}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_high_school_in_aus" class="custom_label">Did you complete High School in Australia?</label>
                        <select name="tutor_application_high_school_in_aus" id="tutor_application_high_school_in_aus" class="form-control" wire:model="tutor_application_high_school_in_aus" x-model="tutor_application_high_school_in_aus">
                            <option value=""></option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="row" x-show="tutor_application_high_school_in_aus == 'Yes'" id="tutor_school_history">
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_school" class="custom_label">What school did you go to?</label>
                        <input type="text" name="tutor_application_school" class="form-control" id="tutor_application_school" wire:model="tutor_application_school" x-model="tutor_application_school">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_atar" class="custom_label">What ATAR did you receive?</label>
                        <select name="tutor_application_atar" id="tutor_application_atar" class="form-control" wire:model="tutor_application_atar" x-model="tutor_application_atar">
                            <option value=""></option>
                            <option value="95+">95+</option>
                            <option value="90-95">90-95</option>
                            <option value="80-89">80-89</option>
                            <option value="70-79">70-79</option>
                            <option value="60-69">60-69</option>
                            <option value="50-59">50-59</option>
                            <option value="Below 50">Below 50</option>
                            <option value="Other or NA">Other or NA</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="tutor_application_achievements" class="custom_label">During your High School career, what would you say was your proudest academic achievement?</label>
                        <textarea name="tutor_application_achievements" class="form-control" id="tutor_application_achievements" cols="30" rows="10" wire:model="tutor_application_achievements" x-model="tutor_application_achievements"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="tutor_application_current_situation" class="custom_label">What best describes your current academic situation?</label>
                        <select name="tutor_application_current_situation" id="tutor_application_current_situation" class="form-control" wire:model="tutor_application_current_situation" x-model="tutor_application_current_situation">
                            <option value=""></option>
                            <option value="I am studying at University">I am studying at University</option>
                            <option value="I plan on studying at University in the next 12 months">I plan on studying at University in the next 12 months</option>
                            <option value="I have graduated from University">I have graduated from University</option>
                            <option value="I am not currently studying">I am not currently studying</option>
                        </select>
                    </div>
                </div>
                <div class="row" x-show="tutor_application_current_situation == 'I am studying at University'" id="tutor_current_university">
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_current_university" class="custom_label">What University do you attend?</label>
                        <input type="text" name="tutor_application_current_university" class="form-control" id="tutor_application_current_university" wire:model="tutor_application_current_university" x-model="tutor_application_current_university">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_current_degree" class="custom_label">What are you studying?</label>
                        <input type="text" name="tutor_application_current_degree" class="form-control" id="tutor_application_current_degree" wire:model="tutor_application_current_degree" x-model="tutor_application_current_degree">
                    </div>
                </div>
                <div class="row" x-show="tutor_application_current_situation == 'I have graduated from University'" id="tutor_graduated_university">
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_graduated_university" class="custom_label">What University did you attend?</label>
                        <input type="text" name="tutor_application_graduated_university" class="form-control" id="tutor_application_graduated_university" wire:model="tutor_application_graduated_university" x-model="tutor_application_graduated_university">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_graduated_degree" class="custom_label">What degree did you graduate with?</label>
                        <input type="text" name="tutor_application_graduated_degree" class="form-control" id="tutor_application_graduated_degree" wire:model="tutor_application_graduated_degree" x-model="tutor_application_graduated_degree">
                    </div>
                </div>
                <div class="row" x-show="tutor_application_current_situation == 'I plan on studying at University in the next 12 months'" id="tutor_future_university">
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_future_university" class="custom_label">What University do you plan on attending?</label>
                        <input type="text" name="tutor_application_future_university" class="form-control" id="tutor_application_future_university" wire:model="tutor_application_future_university" x-model="tutor_application_future_university">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tutor_application_future_degree" class="custom_label">What degree will you be studying?</label>
                        <input type="text" name="tutor_application_future_degree" class="form-control" id="tutor_application_future_degree" wire:model="tutor_application_future_degree" x-model="tutor_application_future_degree">
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <input type="button" id="prev_page_2" class="btn btn-light py-2" value="Previous" x-on:click="goStep(2)">
                        <input type="button" id="to_page_4" class="btn btn-light py-2" value="Next" x-on:click="toStep4">
                    </div>
                </div>
            </form>
        </template>
        <template id="step-4" x-if="step == 4">
            <form action="#" id="tutor_experiences">
                <div class="row mb-3">
                    <div class="col-12">
                        <h2>Your experience:</h2>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="tutor_application_tutored_before" class="custom_label">Have you had any experience tutoring before?</label>
                        <select name="tutor_application_tutored_before" id="tutor_application_tutored_before" class="form-control" wire:model="tutor_application_tutored_before" x-model="tutor_application_tutored_before">
                            <option value=""></option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3" x-show="tutor_application_tutored_before == 'Yes'" id="tutor_experience">
                    <div class="col-12">
                        <label for="tutor_application_experience" class="custom_label">Please share a bit about this experience:</label>
                        <textarea name="tutor_application_experience" class="form-control" id="tutor_application_experience" cols="30" rows="10" wire:model="tutor_application_experience" x-model="tutor_application_experience"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="tutor_application_good_tutor" class="custom_label">Why do you feel you would be a great tutor?</label>
                        <textarea name="tutor_application_good_tutor" class="form-control" id="tutor_application_good_tutor" cols="30" rows="10" wire:model="tutor_application_good_tutor" x-model="tutor_application_good_tutor"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 application_subjects">
                        <label for="tutor_application_subjects" class="custom_label">What subjects would you feel confident tutoring? <span style="color: red;">*</span></label>

                        <ul id="tutor_application_subjects mb-0">
                            @foreach ($all_subjects as $subject)
                            <li x-show="tutor_application_state == '{{$subject->state->name}}'">
                                <input type="checkbox" name="tutor_application_subjects" id="tutor_application_subjects_{{$subject->id}}" value="{{$subject->name}}" wire:model="tutor_application_subjects" x-model="tutor_application_subjects">
                                <label for="tutor_application_subjects_{{$subject->id}}">{{$subject->name}}</label>
                            </li>
                            @endforeach
                        </ul>
                        <label for="tutor_application_subjects" class="error subject_error" x-ref="tutor_application_subjects_error" style="display:none;">This field is required</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="tutor_application_car" class="custom_label">Do you have your drivers license?</label>
                        <select name="tutor_application_car" id="tutor_application_car" class="form-control" wire:model="tutor_application_car" x-model="tutor_application_car">
                            <option value=""></option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3" x-show="tutor_application_car == 'Yes'" id="car_easy">
                    <div class="col-12">
                        <label for="tutor_application_car_easy" class="custom_label">Do you have easy access to a car (yours or family)?</label>
                        <select name="tutor_application_car_easy" id="tutor_application_car_easy" class="form-control" wire:model="tutor_application_car_easy" x-model="tutor_application_car_easy">
                            <option value=""></option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <input type="button" id="prev_page_3" class="btn btn-light py-2" value="Previous" x-on:click="goStep(3)">
                        <input type="button" id="to_page_5" class="btn btn-light py-2" value="Next" x-on:click="toStep5">
                    </div>
                </div>
            </form>
        </template>
        <template id="step-5" x-if="step == 5">
            <form action="#" id="tutor_about_you">
                <div class="row mb-3">
                    <div class="col-12">
                        <h2>About you:</h2>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="tutor_application_introduction" class="custom_label">Imagine we meet at a party. Give us your intro - what are you currently doing, what are you interested in and what are your plans for the future?</label>
                        <textarea name="tutor_application_introduction" class="form-control" id="tutor_application_introduction" cols="30" rows="10" wire:model="tutor_application_introduction" x-model="tutor_application_introduction"></textarea>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <label for="tutor_application_dinner" class="custom_label">If you could have dinner with any 3 people alive or dead, who would they be and why?</label>
                        <textarea name="tutor_application_dinner" class="form-control" id="tutor_application_dinner" cols="30" rows="10" wire:model="tutor_application_dinner" x-model="tutor_application_dinner"></textarea>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <label for="tutor_application_advice" class="custom_label">You become a time traveler and have the opportunity to visit your 15 year-old self, but you can only stay for 30 seconds. What advice would you offer them?</label>
                        <textarea name="tutor_application_advice" class="form-control" id="tutor_application_advice" cols="30" rows="10" wire:model="tutor_application_advice" x-model="tutor_application_advice"></textarea>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <input type="button" id="prev_page_4" class="btn btn-light py-2" value="Previous" x-on:click="goStep(4)">
                        <input type="button" id="to_page_6" class="btn btn-light py-2" value="Next" x-on:click="toStep6">
                    </div>
                </div>
            </form>
        </template>
        <template id="step-6" x-if="step == 6">
            <form action="#" id="tutor_character_refer">
                <div class="row mb-3">
                    <div class="col-12">
                        <h2 class="mb-3">Character References:</h2>
                        <p>As an additional security measure for the families we work with, we ask you to provide two character references that can verify your suitability for the role.</p>
                        <p>These are not professional references - they do not have to be people you have worked with. They can be friends, family members or previous teachers; anyone that can verify you have the qualities we look for in a tutor.</p>
                        <p><a href="/character-references" target="_blank">You can learn more about our character references here.</a></p>
                    </div>
                </div>
                <div class="references_note d-none">
                    <div class="row mb-3">
                        <div class="col-12">
                            <p><b>Because you were referred by one of our tutors we don't require references from you - their reference is good enough!</b></p>
                        </div>
                    </div>
                </div>
                <div class="references">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tutor_application_reference_fname1" class="custom_label">Reference 1 First Name <span style="color: red;">*</span></label>
                            <input type="text" name="tutor_application_reference_fname1" class="form-control" id="tutor_application_reference_fname1" wire:model="tutor_application_reference_fname1" x-model="tutor_application_reference_fname1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tutor_application_reference_lname1" class="custom_label">Reference 1 Last Name <span style="color: red;">*</span></label>
                            <input type="text" name="tutor_application_reference_lname1" class="form-control" id="tutor_application_reference_lname1" wire:model="tutor_application_reference_lname1" x-model="tutor_application_reference_lname1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tutor_application_reference_email1" class="custom_label">Reference 1 Email Address <span style="color: red;">*</span></label>
                            <input type="email" name="tutor_application_reference_email1" class="form-control" id="tutor_application_reference_email1" wire:model="tutor_application_reference_email1" x-model="tutor_application_reference_email1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tutor_application_reference_relationship1" class="custom_label">Reference 1 Relationship <span style="color: red;">*</span></label>
                            <select name="tutor_application_reference_relationship1" id="tutor_application_reference_relationship1" class="form-control" wire:model="tutor_application_reference_relationship1" x-model="tutor_application_reference_relationship1">
                                <option value=""></option>
                                <option value="Family member">Family member</option>
                                <option value="Friend">Friend</option>
                                <option value="Former teacher">Former teacher</option>
                                <option value="Employer or colleague">Employer or colleague</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tutor_application_reference_fname2" class="custom_label">Reference 2 First Name <span style="color: red;">*</span></label>
                            <input type="text" name="tutor_application_reference_fname2" class="form-control" id="tutor_application_reference_fname2" wire:model="tutor_application_reference_fname2" x-model="tutor_application_reference_fname2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tutor_application_reference_lname2" class="custom_label">Reference 2 Last Name <span style="color: red;">*</span></label>
                            <input type="text" name="tutor_application_reference_lname2" class="form-control" id="tutor_application_reference_lname2" wire:model="tutor_application_reference_lname2" x-model="tutor_application_reference_lname2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tutor_application_reference_email2" class="custom_label">Reference 2 Email Address <span style="color: red;">*</span></label>
                            <input type="email" name="tutor_application_reference_email2" class="form-control" id="tutor_application_reference_email2" wire:model="tutor_application_reference_email2" x-model="tutor_application_reference_email2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tutor_application_reference_relationship2" class="custom_label">Reference 2 Relationship <span style="color: red;">*</span></label>
                            <select name="tutor_application_reference_relationship2" id="tutor_application_reference_relationship2" class="form-control" wire:model="tutor_application_reference_relationship2" x-model="tutor_application_reference_relationship2">
                                <option value=""></option>
                                <option value="Family member">Family member</option>
                                <option value="Friend">Friend</option>
                                <option value="Former teacher">Former teacher</option>
                                <option value="Employer or colleague">Employer or colleague</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <input type="button" id="prev_page_5" class="btn btn-light py-2" value="Previous" x-on:click="goStep(5)">
                        <input type="button" id="to_page_7" class="btn btn-light py-2" value="Next" x-on:click="toStep7">
                    </div>
                </div>
            </form>
        </template>
        <template id="step-7" x-if="step == 7">
            <div>
                <div class="row mb-3">
                    <div class="col-12">
                        <h2 class="mb-3">Compliance Stuff:</h2>
                        <p>If successful we will guide you through both of these in detail during your on-boarding.</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="" class="custom_label">I understand I will need a valid working with children check or blue card to register as a tutor <span style="color: red;">*</span></label>
                        <div class="check_rule">
                            <input type="checkbox" name="wwcc_check" id="wwcc_check" wire:model="wwcc_check" x-model="wwcc_check">
                            <label for="wwcc_check">Yes, I understand</label>
                            <label for="" class="error" x-ref="wwcc_check_error" style="display:none;">This field is required.</label>
                        </div>
                        <p class="mt-2" style="font-size: 13px;">If you are under 18, you will be prompted to apply for your WWCC 2 weeks before your 18th birthday.</p>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <label for="" class="custom_label">I understand I will need to submit an ABN (Australian Business Number) to receive payment for any work I do <span style="color: red;">*</span></label>
                        <div class="check_rule">
                            <input type="checkbox" name="abn_check" id="abn_check" wire:model="abn_check" x-model="abn_check">
                            <label for="abn_check">Yes, I understand</label>
                            <label for="" class="error" x-ref="abn_check_error" style="display:none;">This field is required.</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <input type="button" id="prev_page_6" class="btn btn-light py-2" value="Previous" x-on:click="goStep(6)">
                        <input type="button" id="to_page_8" class="btn btn-light py-2" value="Next" x-on:click="toStep8">
                    </div>
                </div>
            </div>
        </template>
        <template id="step-8" x-if="step == 8">
            <div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="custom_style">
                            <p style="font-weight: 600;">This is a preview of your submission. It has not been submitted yet!</p>
                            <p class="mb-0">Please take a moment to verify your information and then hit submit at the bottom. You can also go back to make any changes.</p>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Your name</p>
                    <p><span x-text="tutor_application_first_name"></span> <span x-text="tutor_application_last_name"></span></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Your email address</p>
                    <p x-text="tutor_application_email_address"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Your phone number</p>
                    <p x-text="tutor_application_phone_number"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">What state are you in?</p>
                    <p x-text="tutor_application_state"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">What is your postcode?</p>
                    <p x-text="tutor_application_postal"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">What suburb do you live in?</p>
                    <p x-text="tutor_application_suburb"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">How did you hear about us?</p>
                    <p x-text="tutor_application_source"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">If you have a referral code, please enter it here</p>
                    <p x-text="tutor_application_referral_code"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">What year did you graduate from High School?</p>
                    <p x-text="tutor_application_graduated_year"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Did you complete High School in Australia?</p>
                    <p x-text="tutor_application_high_school_in_aus"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_high_school_in_aus == 'Yes'">
                    <p class="fw-bold">What school did you go to?</p>
                    <p x-text="tutor_application_school"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_high_school_in_aus == 'Yes'">
                    <p class="fw-bold">What ATAR did you receive?</p>
                    <p x-text="tutor_application_atar"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">During your High School career, what would you say was your proudest academic achievement?</p>
                    <p x-text="tutor_application_achievements"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">What best describes your current academic situation?</p>
                    <p x-text="tutor_application_current_situation"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_current_situation == 'I am studying at University'" >
                    <p class="fw-bold">What University do you attend?</p>
                    <p x-text="tutor_application_current_university"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_current_situation == 'I am studying at University'" >
                    <p class="fw-bold">What are you studying?</p>
                    <p x-text="tutor_application_current_degree"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_current_situation == 'I have graduated from University'" >
                    <p class="fw-bold">What University did you attend?</p>
                    <p x-text="tutor_application_current_university"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_current_situation == 'I have graduated from University'" >
                    <p class="fw-bold">What degree did you graduate with?</p>
                    <p x-text="tutor_application_graduated_degree"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_current_situation == 'I plan on studying at University in the next 12 months'" >
                    <p class="fw-bold">What University do you plan on attending?</p>
                    <p x-text="tutor_application_future_university"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_current_situation == 'I plan on studying at University in the next 12 months'" >
                    <p class="fw-bold">What degree will you be studying?</p>
                    <p x-text="tutor_application_future_degree"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Have you had any experience tutoring before?</p>
                    <p x-text="tutor_application_tutored_before"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_tutored_before == 'Yes'" >
                    <p class="fw-bold">Please share a bit about this experience:</p>
                    <p x-text="tutor_application_experience"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_tutored_before == 'Yes'" >
                    <p class="fw-bold">Why do you feel you would be a great tutor?</p>
                    <p x-text="tutor_application_good_tutor"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">What subjects would you feel confident tutoring? </p>
                    <p x-text="tutor_application_subjects"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Do you have your drivers license?</p>
                    <p x-text="tutor_application_car"></p>
                </div>
                <div class="mb-2" x-show="tutor_application_car == 'Yes'" >
                    <p class="fw-bold">Do you have easy access to a car (yours or family)?</p>
                    <p x-text="tutor_application_car_easy"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Imagine we meet at a party. Give us your intro - what are you currently doing, what are you interested in and what are your plans for the future?</p>
                    <p x-text="tutor_application_introduction"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">If you could have dinner with any 3 people alive or dead, who would they be and why?</p>
                    <p x-text="tutor_application_dinner"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">You become a time traveler and have the opportunity to visit your 15 year-old self, but you can only stay for 30 seconds. What advice would you offer them?</p>
                    <p x-text="tutor_application_advice"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Reference 1 First Name </p>
                    <p x-text="tutor_application_reference_fname1"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Reference 1 Last Name </p>
                    <p x-text="tutor_application_reference_lname1"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Reference 1 Email Address </p>
                    <p x-text="tutor_application_reference_email1"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Reference 1 Relationship </p>
                    <p x-text="tutor_application_reference_relationship1"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Reference 2 First Name </p>
                    <p x-text="tutor_application_reference_fname2"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Reference 2 Last Name </p>
                    <p x-text="tutor_application_reference_lname2"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Reference 2 Email Address </p>
                    <p x-text="tutor_application_reference_email2"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">Reference 2 Relationship </p>
                    <p x-text="tutor_application_reference_relationship2"></p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">I understand I will need a valid working with children check or blue card to register as a tutor</p>
                    <p x-show="wwcc_check == true">Yes, I understand</p>
                </div>
                <div class="mb-2">
                    <p class="fw-bold">I understand I will need to submit an ABN (Australian Business Number) to receive payment for any work I do </p>
                    <p x-show="abn_check == true">Yes, I understand</p>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <input type="button" id="prev_page_7" class="btn btn-light py-2" value="Previous" x-on:click="goStep(7)">
                        <input type="button" id="submit" class="btn bg-custom-warning py-2" value="Submit" x-on:click="submit" style="font-size:0.9rem;"
                        x-show="loading == false">
                        <div class="me-3 spinner-border text-muted" x-show="loading == true"></div>
                    </div>
                    <div x-show="submit_result == true" class="custom_style final_error d-none mt-3" style="background-color: #feeef6;">
                        <p class="m-0">Something went wrong! Please try it in a bit later again!</p>
                        <span></span>
                    </div>
                </div>
            </div>
        </template>

    </div>
    <div class="container-fluid bg-black text-center py-5">
        <img src="/images/success_logo.png" width="100" />
    </div>
</div>



<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('init', () => ({
            step: 1,
            tutor_application_first_name: '',
            tutor_application_last_name: '',
            tutor_application_email_address: '',
            tutor_application_phone_number: '',
            tutor_application_state: '',
            tutor_application_postal: '',
            tutor_application_suburb: '',
            tutor_application_source: '',
            tutor_application_referral_code: '',
            tutor_application_graduated_year: '',
            tutor_application_high_school_in_aus: '',
            tutor_application_school: '',
            tutor_application_atar: '',
            tutor_application_achievements: '',
            tutor_application_current_situation: '',
            tutor_application_current_university: '',
            tutor_application_current_degree: '',
            tutor_application_graduated_university: '',
            tutor_application_graduated_degree: '',
            tutor_application_future_university: '',
            tutor_application_future_degree: '',
            tutor_application_tutored_before: '',
            tutor_application_experience: '',
            tutor_application_good_tutor: '',
            tutor_application_subjects: [],
            tutor_application_car: '',
            tutor_application_car_easy: '',
            tutor_application_introduction: '',
            tutor_application_dinner: '',
            tutor_application_advice: '',
            tutor_application_reference_fname1: '',
            tutor_application_reference_lname1: '',
            tutor_application_reference_email1: '',
            tutor_application_reference_relationship1: '',
            tutor_application_reference_fname2: '',
            tutor_application_reference_lname2: '',
            tutor_application_reference_email2: '',
            tutor_application_reference_relationship2: '',
            wwcc_check: false,
            abn_check: false,
            submit_result: true,
            loading: false,

            init() {
                this.goStep(1);
            },
            goStep(step) {
                this.step = step;
                window.scrollTo(0, 0);
                this.$refs.progress_step.style.width = (step * 100 / 8) + '%';
            },
            toStep3() {
                $('#tutor_contact').validate({
                    rules: {
                        tutor_application_first_name: 'required',
                        tutor_application_last_name: 'required',
                        tutor_application_email_address: {
                            required: true,
                            email: true,
                        },
                        tutor_application_phone_number: {
                            required: true,
                        },
                        tutor_application_state: 'required',
                        tutor_application_postal: {
                            required: true,
                            digits: true,
                            minlength: 4,
                            maxlength: 4,
                        },
                    }
                });
                if ($('#tutor_contact').valid()) {
                    this.goStep(3);
                }
            },
            toStep4() {
                $('#tutor_education').validate({
                    rules: {
                        tutor_application_atar: 'required',
                    }
                });
                if ($('#tutor_education').valid()) {
                    this.goStep(4);
                }

            },
            toStep5() {
                if (this.tutor_application_subjects.length > 0) {
                    this.$refs.tutor_application_subjects_error.style.display = 'none';
                    this.goStep(5);
                } else {
                    this.$refs.tutor_application_subjects_error.style.display = 'block';
                }
            },
            toStep6() {
                this.goStep(6);
            },
            toStep7() {
                $.validator.addMethod("notEqual", function(value, element, params) {
                    if ($(params).val() == value) return false
                    else return true
                }, "Please enter a Unique Value.")
                $('#tutor_character_refer').validate({
                    rules: {
                        tutor_application_reference_fname1: 'required',
                        tutor_application_reference_lname1: 'required',
                        tutor_application_reference_fname2: 'required',
                        tutor_application_reference_lname2: 'required',
                        tutor_application_reference_email1: {
                            required: true,
                            email: true,
                        },
                        tutor_application_reference_relationship1: 'required',
                        tutor_application_reference_email2: {
                            required: true,
                            email: true,
                            notEqual: '#tutor_application_reference_email1'
                        },
                        tutor_application_reference_relationship2: 'required'
                    }
                });
                if ($('#tutor_character_refer').valid()) {
                    this.goStep(7);
                }
            },
            toStep8() {
                if (this.wwcc_check) {
                    this.$refs.wwcc_check_error.style.display = 'none';
                } else {
                    this.$refs.wwcc_check_error.style.display = 'block';
                }
                if (this.abn_check) {
                    this.$refs.abn_check_error.style.display = 'none';
                } else {
                    this.$refs.abn_check_error.style.display = 'block';
                }

                if (!!this.wwcc_check && !!this.abn_check) this.goStep(8);
            },
            async submit() {
                this.loading = true;
                try {
                    let result = await @this.call('submitTutorApplication');
                    if (result == true) {
                        location.href='/application-success';
                    }
                    this.submit_result = result;
                    this.loading = false;
                    console.log('result', result);
                } catch (error) {
                    toastr.error(error.message);
                }
                this.loading = false;
            }
        }))
    })
</script>