<div x-data="init" class="bg-light" style="min-height: 100vh;">
    @section('title')
    Alchemy | Tuition Registration
    @endsection
    @section('description')
    @endsection
    @section('script')
    @vite(['resources/css/tutor/register.css'])
    @endsection

    <div x-show="step == 0" class="w-100 h-100 position-fixed py-1 px-3 text-center text-white" style="background-image: url(/images/tutor_register.jpg); background-size: cover; background-position: center;">
        <div class="mb-1">
            <img src="/images/logo_white.png" width="200" />
        </div>
        <h1 class="fst-italic mb-3">Register as an Alchemy tutor.</h1>
        <p class="mb-0">Your registration and onboarding will take about 30 minutes to complete.</p>
        <p class="mb-4">
            We recommend using a desktop device as some of the training components may not display correctly on mobile devices.
        </p>
        <p class="mb-0 fw-bold">To complete your tutor account you will need:</p>
        <p class="mb-0">A photo of yourself for your profile</p>
        <p class="mb-0">A photo of your drivers license or passport to create and verify your STRIPE account (which we use to process payments)</p>
        <p class="mb-0">Your ABN; or if you don't have one, your TFN so you can apply for one (which you will be guided through)</p>
        <p class="mb-4">If you are 18 or older, a valid Working With Children Check for paid work in your state.</p>
        <button class="btn btn-outline-warning px-3 py-2 fst-italic fw-bold fs-5 mb-3" x-on:click="toStep1">Let's do this</button>
    </div>
    <div class="container-fluid" x-show="step > 0">
        <div class="row mb-5 bg-white">
            <div class="col-md text-center py-2 py-md-3" :class="{'bg-info' : step == 1, 'cursor-pointer' : 1 <= max_step}" x-on:click="changeStep(1)">
                <div class="fs-4 fw-bold">Step 1</div>
                <div>Login details</div>
            </div>
            <div class="col-md text-center py-2 py-md-3" :class="{'bg-info' : step == 2, 'cursor-pointer' : 2 <= max_step}" x-on:click="changeStep(2)">
                <div class="fs-4 fw-bold">Step 2</div>
                <div>Personal information</div>
            </div>
            <div class="col-md text-center py-2 py-md-3" :class="{'bg-info' : step == 3, 'cursor-pointer' : 3 <= max_step}" x-on:click="changeStep(3)">
                <div class="fs-4 fw-bold">Step 3</div>
                <div>Availabilities</div>
            </div>
            <div class="col-md text-center py-2 py-md-3" :class="{'bg-info' : step == 4, 'cursor-pointer' : 4 <= max_step}" x-on:click="changeStep(4)">
                <div class="fs-4 fw-bold">Step 4</div>
                <div>Payment information</div>
            </div>
            <div class="col-md text-center py-2 py-md-3" :class="{'bg-info' : step == 5, 'cursor-pointer' : 5 <= max_step}" x-on:click="changeStep(5)">
                <div class="fs-4 fw-bold">Step 5</div>
                <div>Working with children</div>
            </div>
            <div class="col-md text-center py-2 py-md-3" :class="{'bg-info' : step == 6, 'cursor-pointer' : 6 <= max_step}" x-on:click="changeStep(6)">
                <div class="fs-4 fw-bold">Step 6</div>
                <div>Your profile</div>
            </div>
            <div class="col-md text-center py-2 py-md-3" :class="{'bg-info' : step == 7, 'cursor-pointer' : 7 <= max_step}" x-on:click="changeStep(7)">
                <div class="fs-4 fw-bold">Step 7</div>
                <div>Tutor Agreement</div>
            </div>
            <div class="col-md text-center py-2 py-md-3" :class="{'bg-info' : step == 8, 'cursor-pointer' : 8 <= max_step}" x-on:click="changeStep(8)">
                <div class="fs-4 fw-bold">Step 8</div>
                <div>Confirm your details</div>
            </div>
        </div>
    </div>
    <template x-if="step == 1">
        <div class="container-fluid bg-white mt-4 py-3">
            <form action="#" class="needs-validation">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="fs-2 fw-bold text-center mb-1">Your details</div>
                        <p class="text-center">You will use these details to access your account.</p>
                        <div class="border p-3 mb-3">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control " name="email" id="email" wire:model="email" x-model="email">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control " name="phone" id="phone" wire:model="phone" x-model="phone">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <div class="input-group input-group-merge">
                                        <input wire:model="password" x-model="password" type="password" id="password" class="form-control" name="password" required>
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="password" class="form-label">{{ __('Confirm password') }}</label>
                                    <div class="col-md-6 mb-2 input-group input-group-merge">
                                        <input wire:model="password_confirmation" x-model="password_confirmation" type="password" id="password_confirmation" class="form-control " name="password_confirmation">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="state" class="form-label">What state are you in</label>
                                    <select name="tutor_application_state" id="state" class="form-select" wire:model="state" x-model="state">
                                        <option value=""></option>
                                        @forelse ($states as $state)
                                        <option value="{{$state->name}}">{{$state->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div x-show="state != ''" class="">
                                    <h3 class="text-danger">Important notes:</h3>
                                    <p x-show="state != 'QLD' && state != 'VIC' && state != 'ACT'">If you are 18 or older you are required to have a completed Working with children check in order to proceed with your registration. If you do not yet have this, please <a href="https://workingwithchildrencheck.com.au/nsw">follow the steps outlined here</a> and return to register once you have received your WWCC number.
                                        If you are not yet 18, you will be able to register and provide your WWCC once you turn 18.</p>
                                    <p x-show="state == 'QLD'">As QLD has a "No card, No start" policy, you will need a valid Working with children check (often known as a blue card) to proceed with your registration. If you do not yet have this, &nbsp;<a href="https://www.qld.gov.au/law/laws-regulated-industries-and-accountability/queensland-laws-and-regulations/regulated-industries-and-licensing/blue-card/applications/apply" target="_blank">please apply for it here</a> and return to register once you have received your card/account number.</p>
                                    <p x-show="state == 'VIC'">If you are 18 or older you will require a completed Working with children check in order to proceed with your registration. If you do not yet have this, <a href="https://workingwithchildrencheck.com.au/vic" target="_blank">please follow the steps here</a> and return to register once you have received your WWCC number.</p>
                                    <p x-show="state == 'ACT'">If you are 18 or older you are required to have a completed Working with children check in order to proceed with your registration. If you do not yet have this, please <a href="https://workingwithchildrencheck.com.au/nsw">follow the steps outlined here</a> and return to register once you have received your WWCC number.
                                        If you are not yet 18, you will be able to register and provide your WWCC once you turn 18.</p>
                                    <p x-show="state == 'ACT'">If you live in the ACT you can choose to submit either an ACT WWCC or a NSW WWCC. </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="toStep2">Next Step</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
    </template>
    <template x-if="step == 2">
        <div class="container-fluid bg-white mt-4 py-3">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="fs-2 fw-bold text-center mb-1">Personal information</div>
                    <div class="border p-3 mb-3">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="first_name" class="form-label">Legal first name as shown on your ID</label>
                                <input type="text" class="form-control " name="first_name" id="first_name" wire:model="first_name" x-model="first_name">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="preferred_first_name" class="form-label">Preferred first name</label>
                                <input type="text" class="form-control " name="preferred_first_name" id="preferred_first_name" wire:model="preferred_first_name" x-model="preferred_first_name">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="last_name" class="form-label">Last name</label>
                                <input type="text" class="form-control " name="last_name" id="last_name" wire:model="last_name" x-model="last_name">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="date_of_birth" class="form-label">Date of birth</label>
                                <input type="text" class="form-control " name="date_of_birth" id="date_of_birth" x-model="date_of_birth">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select" wire:model="gender" x-model="gender">
                                    <option value=""></option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control " name="address" id="address" wire:model="address" x-model="address">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="suburb" class="form-label">Suburb eg. Castle Hill</label>
                                <input type="text" class="form-control " name="suburb" id="suburb" wire:model="suburb" x-model="suburb">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="postcode" class="form-label">Postcode</label>
                                <input type="text" class="form-control " name="postcode" id="postcode" wire:model="postcode" x-model="postcode">
                            </div>
                        </div>
                    </div>
                    <div class="fs-4 fw-bold text-center mb-1">Select the subjects you feel confident tutoring:</div>
                    <p class="text-center ">You can update your choices at anytime from within your dashboard</p>
                    <div class="border p-3 mb-3">
                        <div class="row">
                            @forelse ($all_subjects as $subject)
                            <template x-if="state == '{{$subject->state->name}}'">
                                <div class="col-md-4 subject-item py-2">
                                    <input type="checkbox" class="d-none" value="{{$subject->name}}" id="subject{{$subject->id}}" wire:model="subjects" x-model="subjects" />
                                    <label class="w-100 h-100 p-2 text-white border rounded-4 d-flex align-items-center justify-content-center text-center" for="subject{{$subject->id}}">{{$subject->name}}</label>
                                </div>
                            </template>
                            @empty
                            @endforelse
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-outline-info waves-effect waves-light" x-on:click="goStep(1)">Previous Step</button>
                            <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="toStep3">Next Step</button>
                        </div>
                    </div>
                </div>
            </div>
    </template>
    <template x-if="step == 3">
        <div class="container-fluid bg-white mt-4 py-3">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="fs-2 fw-bold text-center mb-1">Availabilities</div>
                    <p class="text-center">In order to maximise your time we use pre-defined session times with breaks in between to allow you to work with multiple students in an afternoon/day.
                    </p>
                    <p class="text-center">These availabilities determine the job offers you receive and can be updated at any time from within Dashboard.
                    </p>
                    <p class="text-center">If you are looking to take on multiple students, we suggest making yourself fully available - you can always decline a student offer that doesn't work for you.
                    </p>
                    <div class="row mb-3">
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-warning waves-effect waves-light w-100" x-on:click="selectAllAvailabilities">I am completely available</button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-secondary waves-effect waves-light w-100" x-on:click="availabilities = []">I am completely unavailable</button>
                        </div>
                    </div>
                    <div class="availabilities_wrapper mb-3 px-3">
                        <div class="row">
                            @php $i = 0; $temp_av = []; @endphp
                            @foreach ($total_availabilities as $item)
                            <div class="col-md text-center" data-day="{{$item->name}}">
                                <h5>{{$item->name}}</h5>
                                @forelse ($item->getAvailabilitiesName() as $ele)
                                @php
                                $avail_hour = $item->short_name . '-' . $ele;
                                $i++;
                                $temp_av[] = $avail_hour;
                                @endphp
                                <div class="avail_hours p-0 w-100">
                                    <input type="checkbox" class="d-none" id="availability-{{$i}}" value="{{$avail_hour}}" x-model="availabilities" />
                                    <label for="availability-{{$i}}" class="text-center py-1 w-100">{{$ele}}</label>
                                </div>
                                @empty
                                <div>Not exist</div>
                                @endforelse
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-outline-info waves-effect waves-light" x-on:click="goStep(2)">Previous Step</button>
                            <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="toStep4">Next Step</button>
                        </div>
                    </div>
                </div>
            </div>
    </template>
    <div x-show="step == 4">
        <div class="container-fluid bg-white mt-4 py-3">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="fs-2 fw-bold text-center mb-1">Payment information</div>
                    <div class="border p-3 mb-3">
                        <p>All our tutors are engaged as sub-contractors, which means you will require an ABN to receive payment for the work you do. Please watch this 3 minute video on what this means and how it benefits you.</p>
                        <div class="text-center">
                            <iframe width="510" height="300" class="mb-3" src="https://www.youtube.com/embed/NDmfPGzItGs" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen=""></iframe>
                        </div>
                        <p><a href="https://drive.google.com/file/d/1dMeM-qFadRQDk4yOg0xK3JVuEYpG-hiB/view?usp=sharing" class="mb-3" target="_blank">Click here for a step by step guide to obtaining your ABN</a></p>
                        <p>Please note that payments cannot be processed without your ABN. If you are unable to obtain your ABN at this time, you will need to submit it manually to receive payments in the future.</p>
                        <div class="row">
                            <div class="col-md-12 mb-2" x-show="!not_existing_ABN">
                                <label for="ABN" class="form-label">ABN</label>
                                <input type="text" class="form-control " name="ABN" id="ABN" wire:model="ABN" x-model="ABN">
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="not_existing_ABN" id="not_existing_ABN" x-model="not_existing_ABN">
                                    <label for="not_existing_ABN" class="form-check-label">I will submit my ABN later. I understand I will not receive payment until my ABN has been submitted.</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="bank_account_name" class="form-label">Bank account name</label>
                                <input type="text" class="form-control " name="bank_account_name" id="bank_account_name" wire:model="bank_account_name" x-model="bank_account_name">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="bsb" class="form-label">BSB</label>
                                <input type="text" class="form-control " name="bsb" id="bsb" wire:model="bsb" x-model="bsb">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="bank_account_number" class="form-label">Account number</label>
                                <input type="text" class="form-control " name="bank_account_number" id="bank_account_number" wire:model="bank_account_number" x-model="bank_account_number">
                            </div>
                            <div class="col-md-12 mb-2">
                                <p>We use stripe to process instant payments after each session. To create your STRIPE account, they need to verify your identity. Please upload a photo of your passport or driver's license to confirm your identity.</p>
                                <p><span class="fw-bold text-decoration-underline">Upload a photo of your ID</span>(Max upload size is 2Mb)</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="gender" class="form-label">Please select acceptable forms of identification</label>
                                <select name="gender" id="gender" class="form-select" x-model="acceptable_form">
                                    <option value="1">Passport</option>
                                    <option value="2">Driver License</option>
                                    <option value="3">Photo Card</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <p x-show="acceptable_form != 1">At this time, Stripe does not accept digital cards - please take a photo of the front and back of your physical card and upload below.</p>
                            </div>
                            <div class="col-md-6">
                                <p class="fw-bold">Your ID image</p>
                                <div>
                                    <input type="file" class="photo-image-pond" name="photo-image-pond" id="photo-image-pond" accept="image/*" data-max-file-size="10MB" x-show="photo_image_id != ''" />
                                </div>
                            </div>
                            <div class="col-md-6" x-show="acceptable_form != 1">
                                <p class="fw-bold">Your ID back image</p>
                                <div>
                                    <input type="file" class="photo-back-image-pond" name="photo-back-image-pond" id="photo-back-image-pond" accept="image/*" data-max-file-size="10MB" x-show="photo_image_back_id != ''"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-outline-info waves-effect waves-light" x-on:click="goStep(3)">Previous Step</button>
                            <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="toStep5">Next Step</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <template x-if="step == 5">
        <div class="container-fluid bg-white mt-4 py-3">
            <form action="#" class="needs-validation">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="fs-2 fw-bold text-center mb-1">Working with children check</div>
                        <div class="border p-3 mb-3" x-show="age >= 18">
                            <p>It is a legal requirement that anyone working with people under the age of 18 hold a valid working with children check for paid work.</p>
                            <p>If you do not yet have a completed working with children check, please follow the steps here and return to complete your registration once you have your WWCC number.</p>
                            <p>You can learn more about our child safety policies here.</p>
                            <p>If you are under 18 you cannot apply for a WWCC and it is therefore not required until you turn 18.</p>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="wwcc_fullname" class="form-label">Full name as displayed on your WWCC</label>
                                    <input type="text" class="form-control " name="wwcc_fullname" id="wwcc_fullname" wire:model="wwcc_fullname" x-model="wwcc_fullname">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="wwcc_number" class="form-label">WWCC number</label>
                                    <input type="text" class="form-control " name="wwcc_number" id="wwcc_number" wire:model="wwcc_number" x-model="wwcc_number">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="wwcc_expiry" class="form-label">Expiry</label>
                                    <input type="text" class="form-control " name="wwcc_expiry" id="wwcc_expiry" x-model="wwcc_expiry" />
                                </div>
                            </div>
                        </div>
                        <div class="border p-3 mb-3" x-show="age < 18">
                            <p>A working with children check is a legal requirement for anyone working with young people - however it is not possible to obtain until you turn 18.</p>
                            <p>Child safety is of the highest importance for us. As you are currently under 18, the law still defines you as a child - and we have a responsibility to keep you safe, just as we do for the young people we support. For this reason, we limit tutors that are under 18 to only working with online students until they turn 18 and submit a completed working with children check.</p>
                            <p>You will receive a reminder to complete your working with children check ahead of your 18th birthday. Failure to submit a working with children check within 14 days of turning 18 will result in a termination of your contract - even if you only choose to work with online students. Every Alchemy tutor over the age of 18 must hold a valid working with children check.</p>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="under_wwcc_check" id="under_wwcc_check" x-model="under_wwcc_check" wire:model="under_wwcc_check" />
                                <label for="under_wwcc_check" class="form-check-label">I understand I will only be able to work with online students until I turn 18 and submit my completed working with children check. I will obtain my working with children check within 14 days of turning 18.</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-outline-info waves-effect waves-light" x-on:click="goStep(4)">Previous Step</button>
                                <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="toStep6">Next Step</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
    </template>
    <div x-show="step == 6">
        <div class="container-fluid bg-white mt-4 py-3">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="fs-2 fw-bold text-center mb-1">Your tutor profile</div>
                    <div class="border p-3 mb-3">
                        <div class="mb-2">
                            <p>Your tutor profile is sent to a parent when you accept a student and is the very first impression you give them. We want them to have the confidence that you are THE tutor for the job, which you then backup in your welcome call and by creating an incredible first lesson experience for the student. </p>
                            <p>Please complete your details below. You can view a sample profile here.</p>
                            <p>Try to avoid any specific mentions of your age, the area you live in or school you went to - we want the parent to meet you at the first lesson without any assumptions.</p>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="fw-bold mb-1">Your current status</div>
                                <div class="form-check mb-1">
                                    <input type="radio" id="current_status1" name="current_status" class="form-check-input" value="1" wire:model="current_status" x-model="current_status">
                                    <label class="form-check-label" for="current_status1">Currently studying at University (or planning to attend in the next 12 months)</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input type="radio" id="current_status2" name="current_status" class="form-check-input" value="2" x-model="current_status" wire:model="current_status">
                                    <label class="form-check-label" for="current_status2">Graduated from University in the last 3 years</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input type="radio" id="current_status3" name="current_status" class="form-check-input" value="3" x-model="current_status" wire:model="current_status">
                                    <label class="form-check-label" for="current_status3">Other</label>
                                </div>
                            </div>
                            <div class="col-md-6" x-show="current_status == 1">
                                <div class="mb-2">
                                    <label for="degree1" class="form-label">What is your degree</label>
                                    <input type="text" class="form-control " name="degree1" id="degree1" wire:model="degree" x-model="degree">
                                </div>
                            </div>
                            <div class="col-md-6" x-show="current_status == 1">
                                <div class="mb-2">
                                    <label for="currentstudy1" class="form-label">What university are you attending</label>
                                    <input type="text" class="form-control " name="currentstudy1" id="currentstudy1" wire:model="currentstudy" x-model="currentstudy">
                                </div>
                            </div>
                            <div class="col-md-6" x-show="current_status == 2">
                                <div class="mb-2">
                                    <label for="degree2" class="form-label">What degree do you hold</label>
                                    <input type="text" class="form-control " name="degree2" id="degree2" wire:model="degree" x-model="degree">
                                </div>
                            </div>
                            <div class="col-md-6" x-show="current_status == 2">
                                <div class="mb-2">
                                    <label for="currentstudy2" class="form-label">What University did you graduate from</label>
                                    <input type="text" class="form-control " name="currentstudy2" id="currentstudy2" wire:model="currentstudy" x-model="currentstudy">
                                </div>
                            </div>
                            <div class="col-md-6" x-show="current_status != 3">
                                <div class="mb-2">
                                    <label for="career" class="form-label">What is your goal career:</label>
                                    <input type="text" class="form-control " name="career" id="career" wire:model="career" x-model="career">
                                </div>
                            </div>
                            <div class="col-md-6" x-show="current_status != 3"></div>
                            <hr class="d-md-none" />
                            <div class="col-md-6 mb-2">
                                <div class="d-block d-md-flex justify-content-start align-items-center">
                                    <div class="d-flex justify-content-start align-items-center mb-1">
                                        <span class="fw-bold" style="width:110px;">My favourite &nbsp;</span>
                                        <select name="favourite_item" id="favourite_item" class="form-select d-inline" wire:model="favourite_item" x-model="favourite_item" style="width:110px;">
                                            <option value="sport">sport</option>
                                            <option value="movie">movie</option>
                                            <option value="author">author</option>
                                            <option value="band">band</option>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-start align-items-center mb-1">
                                        <span class="fw-bold d-none d-md-block px-2">is</span>
                                        <span class="fw-bold d-block d-md-none text-center" style="width:110px;">&nbsp; is &nbsp;</span>
                                        <input type="text" class="form-control d-inline" name="favourite_content" id="favourite_content" wire:model="favourite_content" x-model="favourite_content" style="width:110px;">
                                    </div>
                                </div>
                            </div>
                            <hr class="d-md-none" />
                            <div class="col-12 fw-bold mb-2">
                                Your favourite book of all time:
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="book_author" class="form-label">Title</label>
                                    <input type="text" class="form-control " name="book_author" id="book_author" wire:model="book_author" x-model="book_author">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="book_title" class="form-label">Author</label>
                                    <input type="text" class="form-control " name="book_title" id="book_title" wire:model="book_title" x-model="book_title">
                                </div>
                            </div>
                            <hr class="d-md-none" />
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="achivement" class="form-label">When I was in school, my greatest accomplishment was...</label>
                                    <textarea class="form-control" wire:model="achivement" id="achivement" rows="3" x-model="achivement"></textarea>
                                </div>
                            </div>
                            <hr class="d-md-none" />
                            <div class="col-12 fw-bold mb-2">
                                Your three biggest hobbies:
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="hobbies_1" class="form-label">1.</label>
                                    <input type="text" class="form-control " name="hobbies_1" id="hobbies_1" wire:model="hobbies_1" x-model="hobbies_1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="hobbies_2" class="form-label">2.</label>
                                    <input type="text" class="form-control " name="hobbies_2" id="hobbies_2" wire:model="hobbies_2" x-model="hobbies_2">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="hobbies_3" class="form-label">3.</label>
                                    <input type="text" class="form-control " name="hobbies_3" id="hobbies_3" wire:model="hobbies_3" x-model="hobbies_3">
                                </div>
                            </div>
                            <hr class="d-md-none" />
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="great_tutor" class="form-label">I think what makes me a great tutor is...</label>
                                    <textarea class="form-control" wire:model="great_tutor" id="great_tutor" rows="3" x-model="great_tutor"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" id="vaccinated" name="vaccinated" class="form-check-input" wire:model="vaccinated" x-model="vaccinated">
                                        <label for="vaccinated" class="form-check-label">I am fully vaccinated against Covid 19</label>
                                    </div>
                                </div>
                                <p>Choosing to disclose your vaccination status is optional, and we do not require proof of vaccination (but a student or family may want to see it at your first lesson). Indicating you have been vaccinated will add a banner to your profile and allow you to take student opportunities where a parent has specifically requested a tutor vaccinated against Covid 19.</p>
                            </div>
                            <div class="col-12">
                                <p class="fw-bold">Profile picture</p>
                                <p class="mb-2">Please select an image that is bright, clear and shows your positive attitude. Avoid using school photos, nightclub photos or anything that a parent may not want to see.</p>
                                <div style="width:200px;">
                                    <input type="file" class="profile-image-pond" name="profile-image-pond" id="profile-image-pond" accept="image/*" data-max-file-size="10MB" x-show="profile_image_id != ''"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-outline-info waves-effect waves-light" x-on:click="goStep(5)">Previous Step</button>
                            <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="toStep7">Next Step</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-show="step == 7" class="container-fluid bg-white mt-4 py-3">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="fs-2 fw-bold text-center mb-1">Tutor Agreement</div>
                <div class="border p-3 mb-3">
                    <div class="text-center mb-3">
                        This is our tutor agreement which outlines expectations, payment rates and other important details you will need to know in working with us. Many of the points will be explained further in your onboarding, such as how payment increases work and processes for things like confirming lessons and accepting students. Please take a few minutes to read through the tutor agreement and add your signature at the bottom.
                    </div>
                    <iframe loading="lazy" width="100%" height="1122" src="https://tutorhub.alchemytuition.com.au/wp-content/plugins/pdf-poster/pdfjs-new/web/viewer.html?file=https://tutorhub.alchemytuition.com.au/wp-content/uploads/2023/01/tutoragreement1.06.pdf&download=true&print=false&openfile=false"></iframe>
                    <div class="signature mb-3 text-center mx-auto" style="width:200px;">
                        <div class="sigPad">
                            <p>Please sign below to show you have read and accept the terms of the recruiter agreement</p>
                            <p class="drawItDesc border-top pt-2">Please draw your signature</p>
                            <div class="sig sigWrapper border">
                                <canvas class="pad" width="198" height="55"></canvas>
                                <input type="hidden" name="output" class="output" id="sign_output" x-ref="sign_output">
                            </div>
                            <div class="clearButton mt-2"><a href="#clear">Clear</a></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-outline-info waves-effect waves-light" x-on:click="goStep(6)">Previous Step</button>
                        <button type="button" class="btn btn-info waves-effect waves-light" x-on:click="toStep8">Next Step</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-show="step == 8" class="container-fluid bg-white mt-4 py-3">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="fs-2 fw-bold text-center mb-1">Confirm your details</div>
                <div class="border p-3 mb-3">
                    <p class="fw-bold mb-3">Please review all your details and if correct, hit submit</p>
                    <div class="row">
                        <div class="col-md-6 fw-bold">Legal first name</div>
                        <div class="col-md-6" x-text="first_name"></div>
                        <div class="col-md-6 fw-bold">Last name</div>
                        <div class="col-md-6" x-text="last_name"></div>
                        <div class="col-md-6 fw-bold">Email</div>
                        <div class="col-md-6" x-text="email"></div>
                        <div class="col-md-6 fw-bold">Phone</div>
                        <div class="col-md-6" x-text="phone"></div>
                        <div class="col-md-6 fw-bold">State</div>
                        <div class="col-md-6" x-text="state"></div>
                        <div class="col-md-6 fw-bold">Date of birth</div>
                        <div class="col-md-6" x-text="date_of_birth"></div>
                        <div class="col-md-6 fw-bold">Gender</div>
                        <div class="col-md-6" x-text="gender"></div>
                        <div class="col-md-6 fw-bold">Address</div>
                        <div class="col-md-6" x-text="address"></div>
                        <div class="col-md-6 fw-bold">Suburb</div>
                        <div class="col-md-6" x-text="suburb"></div>
                        <div class="col-md-6 fw-bold">Postcode</div>
                        <div class="col-md-6" x-text="postcode"></div>
                        <div class="col-md-6 fw-bold">Subjects</div>
                        <div class="col-md-6" x-text="subjects"></div>
                        <div class="col-md-6 fw-bold">Availabilities</div>
                        <div class="col-md-6" x-text="availabilities"></div>
                        <div class="col-md-6 fw-bold">ABN</div>
                        <div class="col-md-6" x-text="ABN"></div>
                        <div class="col-md-6 fw-bold">Bank account name</div>
                        <div class="col-md-6" x-text="bank_account_name"></div>
                        <div class="col-md-6 fw-bold">BSB</div>
                        <div class="col-md-6" x-text="bsb"></div>
                        <div class="col-md-6 fw-bold">Bank account number</div>
                        <div class="col-md-6" x-text="bank_account_number"></div>
                        <div class="col-md-6 fw-bold">Have WWCC</div>
                        <div class="col-md-6" x-show="wwcc_fullname != '' && wwcc_number != '' && wwcc_expiry != ''">Yes</div>
                        <div class="col-md-6" x-show="wwcc_fullname == '' || wwcc_number == '' || wwcc_expiry == ''">No</div>
                        <div class="col-md-6 fw-bold">Full name shown on WWCC</div>
                        <div class="col-md-6" x-text="wwcc_fullname"></div>
                        <div class="col-md-6 fw-bold">WWCC number</div>
                        <div class="col-md-6" x-text="wwcc_number"></div>
                        <div class="col-md-6 fw-bold">Expiry</div>
                        <div class="col-md-6" x-text="wwcc_expiry"></div>
                        <div class="col-md-6 fw-bold">Preferred first name</div>
                        <div class="col-md-6" x-text="preferred_first_name"></div>
                        <div class="col-md-6 fw-bold">Current Status</div>
                        <div class="col-md-6" x-show="current_status == 1">Currently studying at University (or planning to attend in the next 12 months)</div>
                        <div class="col-md-6" x-show="current_status == 2">Graduated from University in the last 3 years</div>
                        <div class="col-md-6" x-show="current_status == 3">Other</div>
                        <div class="col-md-6 fw-bold">Degree</div>
                        <div class="col-md-6" x-text="degree"></div>
                        <div class="col-md-6 fw-bold">Your university</div>
                        <div class="col-md-6" x-text="currentstudy"></div>
                        <div class="col-md-6 fw-bold">Career</div>
                        <div class="col-md-6" x-text="career"></div>
                        <div class="col-md-6 fw-bold">Your favourite</div>
                        <div class="col-md-6">
                            <p class="mb-0" x-show="favourite_item != '' && favourite_content != ''">
                                My favourite <span x-text="favourite_item"></span> is <span x-text="favourite_content"></span>
                            </p>
                        </div>
                        <div class="col-md-6 fw-bold">The title of the favourite book</div>
                        <div class="col-md-6" x-text="book_title"></div>
                        <div class="col-md-6 fw-bold">The author of the favourite book</div>
                        <div class="col-md-6" x-text="book_author"></div>
                        <div class="col-md-6 fw-bold">Greatest accomplishment</div>
                        <div class="col-md-6" x-text="achivement"></div>
                        <div class="col-md-6 fw-bold">Hobbies</div>
                        <div class="col-md-6">
                            <span x-text="hobbies_1"></span>,
                            <span x-text="hobbies_2"></span>,
                            <span x-text="hobbies_3"></span>
                        </div>
                        <div class="col-md-6 fw-bold">What makes you a great tutor</div>
                        <div class="col-md-6" x-text="great_tutor"></div>
                        <div class="col-md-6 fw-bold">Vaccinated status</div>
                        <div class="col-md-6" x-show="vaccinated == true">I am vaccinated against Covid 19.</div>
                        <div class="col-md-6" x-show="vaccinated == false">I am not vaccinated against Covid 19.</div>
                        <div class="col-md-6 fw-bold">Signature</div>
                        <div class="col-md-6 review_sigPad">
                            <canvas class="pad" id="pad_review" width="198" height="55" x-ref="pad_review"></canvas>
                            <input type="hidden" name="signature_img" id="signature_img" x-model="signature_img">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-outline-info waves-effect waves-light" x-on:click="goStep(7)">Previous Step</button>
                        <button type="button" class="btn btn-primary waves-effect waves-light" x-on:click="submit" x-show="loading == false">Submit</button>
                        <div class="ms-3 spinner-border text-muted" x-show="loading == true"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module">
    import {
        // // Image editor
        openEditor,
        processImage,
        createDefaultImageReader,
        createDefaultImageWriter,
        createDefaultImageOrienter,

        // // Only needed if loading legacy image editor data
        legacyDataToImageState,

        // // Import the editor default configuration
        getEditorDefaults,

    } from "{{asset('vendor/filepond/assets/pintura.js')}}";

    // First register any plugins
    $.fn.filepond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginImageEditor,
        FilePondPluginFilePoster
    );

    const filepondOptions = {
        // labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
        credits: false,
        server: {
            url: '/filepond',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            }
        },
        onaddfile: (error, file) => {
            console.log('add', file);
            if (error) {
                console.error('Oh no', error);
                return;
            }
            $("button[type=submit]").prop('disabled', true);
        },
        // FilePond generic properties
        imageResizeTargetWidth: 1024,
        imageResizeMode: 'cover',
        imageResizeUpscale: false,
        allowFilePoster: true,
        allowImageEditor: true,
        filePosterMaxHeight: 256,
        // FilePond Image Editor plugin properties
        imageEditor: {
            // Maps legacy data objects to new imageState objects (optional)
            legacyDataToImageState: legacyDataToImageState,
            // Used to create the editor (required)
            createEditor: openEditor,
            // Used for reading the image data. See JavaScript installation for details on the `imageReader` property (required)
            imageReader: [
                createDefaultImageReader,
            ],
            // Can leave out when not generating a preview thumbnail and/or output image (required)
            imageWriter: [
                createDefaultImageWriter,
                {
                    // We'll resize images to fit a 512 × 512 square
                    targetSize: {
                        width: 512,
                        // height: 512,
                    },
                },
            ],
            // Used to generate poster images, runs an invisible "headless" editor instance. (optional)
            imageProcessor: processImage,
            // Pintura Image Editor options
            editorOptions: {
                ...getEditorDefaults(),
                // // This will set a square crop aspect ratio
                // imageCropAspectRatio: 1,
            }
        }

    };

    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    }

    function checkPhone(phone) {
        // phone = '+61' + phone
        var pattern = /^(?:\+?(61))? ?(?:\((?=.*\)))?(0?[2-57-8])\)? ?(\d\d(?:[- ](?=\d{3})|(?!\d\d[- ]?\d[- ]))\d\d[- ]?\d[- ]?\d{3})$/;
        return pattern.test(phone);
    }

    function isNumeric(value) {
        return /^-?\d+$/.test(value);
    }

    function postal_code(code) {
        var pattern = /^(?:(?:[2-8]\d|9[0-7]|0?[28]|0?9(?=09))(?:\d{2}))$/
        return pattern.test(code)
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('init', () => ({
            step: 8,
            max_step: 8,
            loading: false,
            email: '',
            phone: '',
            password: '',
            password_confirmation: '',
            state: '',
            first_name: '',
            preferred_first_name: '',
            last_name: '',
            date_of_birth: '',
            gender: '',
            address: '',
            suburb: '',
            postcode: '',
            subjects: [],
            availabilities: [],
            ABN: '',
            not_existing_ABN: false,
            bank_account_name: '',
            bank_account_number: '',
            bsb: '',
            acceptable_form: 1,
            photo_image_id: '',
            photo_back_image_id: '',
            wwcc_fullname: '',
            wwcc_number: '',
            wwcc_expiry: '',
            age: 0,
            under_wwcc_check: false,
            current_status: 1,
            degree: '',
            currentstudy: '',
            career: '',
            favourite_item: 'sport',
            favourite_content: '',
            book_author: '',
            book_title: '',
            achivement: '',
            hobbies_1: '',
            hobbies_2: '',
            hobbies_3: '',
            great_tutor: '',
            vaccinated: false,
            profile_image_id: '',
            signature_img: '',

            init() {
                $('.sigPad').signaturePad({
                    drawOnly: true
                });
                $('#photo-image-pond').filepond({
                    ...filepondOptions,
                    onprocessfile: (error, file) => {
                        if (error) {
                            console.error('Oh no', error);
                            return;
                        }

                        $("button[type=submit]").prop('disabled', false);
                        console.log('process', file);
                        this.photo_image_id = file.serverId;
                    },
                    onremovefile: (error, file) => {
                        if (error) {
                            console.log('Oh no', error);
                            return;
                        }
                        $("button[type=submit]").prop('disabled', false);
                        console.log('deleted', file);
                        this.photo_image_id = '';
                    },
                });
                $('#photo-back-image-pond').filepond({
                    ...filepondOptions,
                    onprocessfile: (error, file) => {
                        if (error) {
                            console.error('Oh no', error);
                            return;
                        }

                        $("button[type=submit]").prop('disabled', false);
                        console.log('process', file);
                        this.photo_back_image_id = file.serverId;
                    },
                    onremovefile: (error, file) => {
                        if (error) {
                            console.log('Oh no', error);
                            return;
                        }
                        $("button[type=submit]").prop('disabled', false);
                        console.log('deleted', file);
                        this.photo_back_image_id = '';
                    },
                });
                $('#profile-image-pond').filepond({
                    ...filepondOptions,
                    imagePreviewHeight: 170,
                    imageCropAspectRatio: '1:1',
                    imageResizeTargetWidth: 200,
                    imageResizeTargetHeight: 200,
                    stylePanelLayout: 'compact circle',
                    styleLoadIndicatorPosition: 'center bottom',
                    styleProgressIndicatorPosition: 'right bottom',
                    styleButtonRemoveItemPosition: 'left bottom',
                    styleButtonProcessItemPosition: 'right bottom',
                    // FilePond Image Editor plugin properties
                    imageEditor: {
                        // Maps legacy data objects to new imageState objects (optional)
                        legacyDataToImageState: legacyDataToImageState,

                        // Used to create the editor (required)
                        createEditor: openEditor,

                        // Used for reading the image data. See JavaScript installation for details on the `imageReader` property (required)
                        imageReader: [
                            createDefaultImageReader,
                        ],

                        // Can leave out when not generating a preview thumbnail and/or output image (required)
                        imageWriter: [
                            createDefaultImageWriter,
                            {
                                // We'll resize images to fit a 512 × 512 square
                                targetSize: {
                                    width: 512,
                                    height: 512,
                                },
                            },
                        ],

                        // Used to generate poster images, runs an invisible "headless" editor instance. (optional)
                        imageProcessor: processImage,

                        // Pintura Image Editor options
                        editorOptions: {
                            ...getEditorDefaults(),
                            // // This will set a square crop aspect ratio
                            imageCropAspectRatio: 1,
                        }
                    },
                    onprocessfile: (error, file) => {
                        if (error) {
                            console.error('Oh no', error);
                            return;
                        }
                        $("button[type=submit]").prop('disabled', false);
                        console.log('process', file);
                        this.profile_image_id = file.serverId;
                    },
                    onremovefile: (error, file) => {
                        if (error) {
                            console.log('Oh no', error);
                            return;
                        }
                        $("button[type=submit]").prop('disabled', false);
                        console.log('deleted', file);
                        this.profile_image_id = '';
                    },
                });
            },
            goStep(step) {
                this.step = step;
                if (step > this.max_step) this.max_step = step;
                if (this.step == 2) {
                    let temp = this;
                    setTimeout(() => {
                        $('#date_of_birth').datepicker({
                                autoclose: true,
                                format: 'dd/mm/yyyy',
                                todayHighlight: true,
                            })
                            .on('changeDate', function(e) {
                                var date_of_birth = e.format(0, 'dd/mm/yyyy');
                                temp.date_of_birth = date_of_birth;
                            });
                    }, 100);
                }
                if (this.step == 5) {
                    let temp = this;
                    setTimeout(() => {
                        $('#wwcc_expiry').datepicker({
                                autoclose: true,
                                format: 'dd/mm/yyyy',
                                todayHighlight: true,
                            })
                            .on('changeDate', function(e) {
                                var wwcc_expiry = e.format(0, 'dd/mm/yyyy');
                                temp.wwcc_expiry = wwcc_expiry;
                            });
                    }, 100);
                }
            },
            changeStep(step) {
                if (step <= this.max_step) this.goStep(step);
            },
            toStep1() {
                this.goStep(1);
            },
            async toStep2() {
                try {
                    if (!isValidEmailAddress(this.email)) throw new Error('Please enter a valid email address');
                    if (!checkPhone(this.phone)) throw new Error('Please enter a valid phone number');
                    if (!this.state) throw new Error('Please enter your state');
                    if (!this.password || this.password != this.password_confirmation) throw new Error("Passwords don't match");

                    let res = await axios.get('/check-user', {
                        params: {
                            email: this.email
                        }
                    });
                    let isExistingUser = res.data.result;
                    if (!isExistingUser) {
                        this.goStep(2);
                    } else throw new Error('The user already existed.');

                } catch (error) {
                    toastr.error(error.message);
                }
            },
            toStep3() {
                try {
                    if (!this.first_name) throw new Error('Please enter a valid first name');
                    if (!this.last_name) throw new Error('Please enter a valid last name');
                    if (!this.checkDateOfBirth(this.date_of_birth)) throw new Error('Please enter a valid birthday');
                    if (!this.gender) throw new Error('Please enter a valid gender');
                    if (!this.address) throw new Error('Please enter a valid address');
                    if (!this.suburb) throw new Error('Please enter a valid suburb');
                    if (!postal_code(this.postcode)) throw new Error('Please enter a valid postcode');
                    if (!this.subjects.length > 0) throw new Error('Please select at least 1 subject');

                    this.goStep(3);

                } catch (error) {
                    toastr.error(error.message);
                }
            },
            selectAllAvailabilities() {
                let availabilities = @json($temp_av);
                this.availabilities = availabilities;
            },
            toStep4() {
                try {
                    if (this.availabilities.length == 0) throw new Error('Please select at least an hour');

                    this.goStep(4);
                } catch (error) {
                    toastr.error(error.message);
                }
            },
            toStep5() {
                try {
                    if (!this.not_existing_ABN && !this.ABN) throw new Error('Please enter a valid ABN');
                    if (!this.bank_account_name) throw new Error('Please enter a valid bank account');
                    if (!this.bsb) throw new Error('Please enter a valid BSB');
                    if (!this.bank_account_number) throw new Error('Please enter a valid account number');
                    if (!this.photo_image_id) throw new Error('Please upload a photo of your ID');

                    this.goStep(5);
                } catch (error) {
                    toastr.error(error.message);
                }
            },
            toStep6() {
                try {
                    if (this.age >= 18) {
                        if (!this.wwcc_fullname) throw new Error('Please enter a valid WWCC full name');
                        if (!this.wwcc_expiry) throw new Error('Please enter a valid WWCC expiry date');
                        if (!this.wwcc_number) throw new Error('Please enter a valid WWCC number');
                    } else {
                        if (!this.under_wwcc_check) throw new Error('Please accept the legal requirement.');
                    }

                    this.goStep(6);
                } catch (error) {
                    toastr.error(error.message);
                }
            },
            toStep7() {
                try {
                    if (this.current_status != 3) {
                        if (!this.degree) throw new Error('Please enter a valid degree');
                        if (!this.currentstudy) throw new Error('Please enter your university');
                        if (!this.career) throw new Error('Please enter your career');
                    }
                    if (!this.favourite_item || !this.favourite_content || !this.book_title || !this.book_author || !this.achivement || !this.hobbies_1 || !this.hobbies_2 || !this.hobbies_3 || !this.great_tutor) throw new Error('Please answer all questions!');
                    if (!this.profile_image_id) throw new Error('Please upload your profile image');

                    this.goStep(7);
                } catch (error) {
                    toastr.error(error.message);
                }
            },
            toStep8() {
                try {
                    if (!this.$refs.sign_output.value) throw new Error('Please add your signature.');

                    $('.review_sigPad').signaturePad({
                        displayOnly: true,
                        bgColour: 'transparent',
                        penColour: '#000'
                    }).regenerate(this.$refs.sign_output.value);
                    var canvas = this.$refs.pad_review;
                    this.signature_img = canvas.toDataURL("image/png");

                    this.goStep(8);
                } catch (error) {
                    toastr.error(error.message);
                }
            },
            async submit() {
                this.loading = true;
                try {
                    if (!this.signature_img) throw new Error('Please add your signature.');

                    await @this.call('registerTutor', this.date_of_birth, this.wwcc_expiry, this.availabilities, this.signature_img, this.photo_image_id, this.photo_back_image_id, this.profile_image_id);

                    this.loading = false;
                } catch (error) {
                    toastr.error(error.message);
                }
                this.loading = false;
            },
            checkDateOfBirth(date) {
                var today = new Date().getTime()
                var aus_date = date.split('/');
                date = aus_date[1] + '/' + aus_date[0] + '/' + aus_date[2];
                let dob = new Date(date).getTime();
                let age = today - dob;
                let yoa = Math.floor(age / 1000 / 60 / 60 / 24 / 365.25); // age / ms / sec / min / hour / days in a year
                this.age = yoa;
                console.log(yoa)
                if (date == '') {
                    return false;
                } else if (yoa < 13) {
                    return false;
                } else {
                    return true;
                }
            }
        }));
    });
</script>