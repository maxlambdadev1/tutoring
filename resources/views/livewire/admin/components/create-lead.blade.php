<div class="row" x-data="create_lead_init">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-0">
                    <label for="search_str_for_parent_child" class="form-label">Search By Name Or Email Of Parents & Students</label>
                    <input wire:model="search_str_for_parent_child" wire:keydown="searchParentsChildrenForInputing" type="text" id="search_str_for_parent_child" class="form-control form-control-sm" placeholder="Search Parents And Students">
                </div>
                @if (count($searched_parents_children) > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Parent</th>
                            <th>Child</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($searched_parents_children as $person)
                        <tr class="cursor-pointer" wire:click="selectParentAndChild({{$person}})">
                            <td>{{ $person->parent_first_name}} {{$person->parent_last_name}}({{$person->parent_email}})</td>
                            <td>{{$person->child_name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <x-session-alert />
                <form id="store_lead" action="#">
                    @csrf
                    <div id="lead_wizard">
                        <ul class="nav nav-pills nav-justified form-wizard-header mb-3 mx-0">
                            <li class="nav-item">
                                <a href="#parent" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2" :class="{'active' : current_step == 0}">
                                    <i class="mdi mdi-account-question me-1"></i>
                                    <span class="d-none d-sm-inline">Parent</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#location" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2" :class="{'active' : current_step == 1}">
                                    <i class="mdi mdi-map-marker-radius me-1"></i>
                                    <span class="d-none d-sm-inline">Location</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#student" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2" :class="{'active' : current_step == 2}">
                                    <i class="mdi mdi-account-tie-hat me-1"></i>
                                    <span class="d-none d-sm-inline">Student</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#lessons" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2" :class="{'active' : current_step == 3}">
                                    <i class="mdi mdi-briefcase-edit-outline me-1"></i>
                                    <span class="d-none d-sm-inline">Lessons</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#admin" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2" :class="{'active' : current_step == 4}">
                                    <i class="mdi mdi-account me-1"></i>
                                    <span class="d-none d-sm-inline">Admin</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content b-0 mb-0">
                            <div class="tab-pane" :class="{'active' : current_step == 0}" id="parent">
                                <div class="row">
                                    <div class="col-12">
                                        <x-form-input wire:model="inputData.parent_first_name" name="parent_first_name" type="text" label="Parent first name" placeholder="First name" autocomplete="parent_first_name" />
                                        <x-form-input wire:model="inputData.parent_last_name" name="parent_last_name" type="text" label="Parent last name" placeholder="Last name" autocomplete="parent_last_name" />
                                        <x-form-input wire:model="inputData.parent_phone" name="parent_phone" type="text" label="Parent phone" placeholder="Phone" autocomplete="parent_phone" />
                                        <x-form-input wire:model="inputData.parent_email" name="parent_email" type="text" label="Parent email" placeholder="Email" autocomplete="parent_email" />
                                        <x-form-select-origin wire:model="inputData.lead_source" name="lead_source" :items="$sources" label="Lead Source" />
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" :class="{'active' : current_step == 1}" id="location">
                                <div class="row">
                                    <div class="col-12" x-data="{showAddressSuburb : @entangle('session_type_id') === 1}">
                                        <x-form-select wire:model="inputData.session_type_id" name="session_type_id" :items="$session_types" label="Session Type" x-on:change="showAddressSuburb = ($event.target.value === '1')" />
                                        <x-form-select wire:model="inputData.state_id" name="state_id" :items="$states" label="State" />
                                        <div x-show="showAddressSuburb == true" class="address_suburb">
                                            <x-form-input wire:model="inputData.address" name="address" type="text" label="Address" placeholder="Address" autocomplete="address" />
                                            <x-form-input wire:model="inputData.suburb" name="suburb" type="text" label="Suburb" placeholder="Suburb" autocomplete="suburb" />
                                        </div>
                                        <x-form-input wire:model="inputData.postcode" name="postcode" type="text" label="Postcode" placeholder="Postcode" autocomplete="postcode" />
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" :class="{'active' : current_step == 2}" id="student">
                                <div class="row">
                                    <div class="col-12">
                                        <x-form-input wire:model="inputData.students.student1.student_first_name" name="student_first_name" type="text" label="Student first name" placeholder="First name" autocomplete="student_first_name" />
                                        <x-form-input wire:model="inputData.students.student1.student_last_name" name="student_last_name" type="text" label="Student last name" placeholder="Last name" autocomplete="student_last_name" />
                                        <x-form-select wire:model="inputData.students.student1.grade_id" name="grade_id" :items="$grades" label="Grade" />
                                        <x-form-input wire:model="inputData.students.student1.student_school" name="student_school" type="text" label="Student school" placeholder="Student school" autocomplete="student_school" />
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" :class="{'active' : current_step == 3}" id="lessons" x-data="{showStartDatePicker: @entangle('start_date') === 'DATE'}">
                                <x-form-select-origin wire:model="inputData.students.student1.start_date" name="start_date" :items="['ASAP', 'DATE']" label="Start date" x-on:change="showStartDatePicker = ($event.target.value === 'DATE')" />
                                <div x-show="showStartDatePicker">
                                    <x-form-input wire:model="inputData.students.student1.start_date_picker" name="start_date_picker" type="text" label="Select a date" autocomplete="start_date_picker" />
                                </div>
                                @if ($subjects)
                                <x-form-select-origin wire:model="inputData.students.student1.subject" name="subject" :items="$subjects" label="Subject" />
                                @endif
                                <div class="mb-3">
                                    <label for="" class="form-label">{{__('Availabilities')}}</label>
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
                                                    <input type="checkbox" class="d-none" id="availability-{{$i}}" value="{{$avail_hour}}" wire:model="inputData.students.student1.availabilities" />
                                                    <label for="availability-{{$i}}" class="text-center py-1 w-100">{{$ele}}</label>
                                                </div>
                                                @empty
                                                <div>Not exist</div>
                                                @endforelse
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <x-form-input wire:model="inputData.referral" name="referral" label="Promo code" />
                                <x-form-textarea wire:model="inputData.students.student1.parent_looking" name="parent_looking" label="What is the parent looking for?" />
                                <x-form-textarea wire:model="inputData.students.student1.student_doing" name="student_doing" label="How is the student currently doing at school?" />
                                <x-form-textarea wire:model="inputData.students.student1.additional_details" name="additional_details" label="Are there any additional details about the student that would be relevant to the tutor?" />
                                <x-form-select-origin wire:model="inputData.students.student1.main_result" name="main_result" label="What is the main result you are looking for?" :items="['Get better results at school','Boost confidence','Enjoy the learning experience','A mix of all']" />
                                <x-form-select-origin wire:model="inputData.students.student1.student_performance" name="student_performance" label="How would you describe their current performance at school?" :items="['Struggling at school','Excelling at school','Somewhere in between']" />
                                <x-form-select-origin wire:model="inputData.students.student1.student_attitude" name="student_attitude" label="How would you describe their attitude towards school?" :items="['They love school','They dislike school','Somewhere in between']" />
                                <x-form-select-origin wire:model="inputData.students.student1.student_mind" name="student_mind" label="How would you describe their mind?" :items="['Creative','Analytical','Somewhere in between']" />
                                <x-form-select-origin wire:model="inputData.students.student1.student_personality" name="student_personality" label="How would you describe their personality?" :items="['Shy','Outgoing','Somewhere in between']" />
                                <x-form-input wire:model="inputData.students.student1.student_favourite" name="student_favourite" label="What are their 3 favourite things to do (ie. video games, soccer, swimming)" />
                            </div>
                            <div class="tab-pane" :class="{'active' : current_step == 4}" id="admin">
                                <div class="row">
                                    <div class="col-12" x-data="{tutorApplyOffer: false, parentApplyDiscount: false, isSpecialRequest: false}">
                                        <x-form-select-origin wire:model="inputData.prefered_gender" name="prefered_gender" label="Preferred gender" :items="['Male','Female']" />
                                        <div class="mb-3" x-data="{showDropdown: false}">
                                            <label class="form-label" for="phone">Ignore tutors</label>
                                            <div class="input-group" id="ignore-tutors-input-group dropdown-toggle" data-bs-toggle="dropdown">
                                                <input wire:model="search_str_for_tutors" id="search_str_for_tutors" name="search_str_for_tutors" wire:keydown="searchTutorsForIgnore" x-on:focus="showDropdown = true" x-on:blur="setTimeout(() => showDropdown = false, 150)" type="text" class="form-control" placeholder="Type here to find the tutors (Name or Email)...">
                                            </div>
                                            <ul class="dropdown-menu dropdown-menu-md p-2" :class="{'d-block show' : showDropdown}">
                                                @forelse ($searched_tutors as $tutor)
                                                <li class="cursor-pointer" wire:click="addTutorToIgnore({{ $tutor }})" x-on:click="showDropdown = false">
                                                    <a class="dropdown-item">
                                                        <div>{{ $tutor->tutor_name }}</div>
                                                        <span>{{ $tutor->user->email }}</span>
                                                    </a>
                                                </li>
                                                @empty
                                                <li class="cursor-pointer">
                                                    <a class="dropdown-item">
                                                        <span>There are no tutors</span>
                                                    </a>
                                                </li>
                                                @endforelse
                                            </ul>
                                            <div class="border border-light p-1">
                                                <ul class="ignore_tutors_list">
                                                    @foreach ($inputData['ignore_tutors'] as $tutor)
                                                    <li class="bg-primary mb-1 item">
                                                        <button type="button" wire:click="removeTutorFromIgnore({{ $tutor}})"><i class="mdi mdi-close"></i></button>
                                                        <span>{{$tutor->first_name}} {{ $tutor->last_name}}({{ $tutor->user->email }})</span>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <x-form-textarea wire:model="inputData.students.student1.team_notes" name="team notes" label="Additional information(Comment for the team)" />
                                        <x-form-checkbox-custom wire:model="inputData.tutor_apply_offer" name="tutor_apply_offer" label="Apply tutor offer" x-model="tutorApplyOffer" />
                                        <div x-show="tutorApplyOffer == true" class="apply_offer" style="display: none;">
                                            <div class="mb-3">
                                                <label for="offer_type" class="form-label">Offer type</label>
                                                <select class="form-select" wire:model="inputData.offer_type" id="offer_type">
                                                    <option value="">Please select</option>
                                                    <option value="fixed">Fixed</option>
                                                    <option value="percentage">Percent</option>
                                                </select>
                                            </div>
                                            <x-form-input wire:model="inputData.offer_amount" name="offer_amount" label="Offer amount" />
                                            <x-form-select-origin wire:model="inputData.offer_valid" name="offer_valid" label="Valid until" :items="$offer_valid_list" />
                                        </div>
                                        <hr>
                                        <x-form-checkbox-custom wire:model="inputData.parent_apply_discount" name="parent_apply_discount" label="Apply parent discount" x-model="parentApplyDiscount" />
                                        <div x-show="parentApplyDiscount == true" class="apply_discount" style="display: none;">
                                            <div class="mb-3">
                                                <label for="inputData.discount_type" class="form-label">Discount type</label>
                                                <select class="form-select" wire:model="inputData.discount_type" id="discount_type">
                                                    <option value="">Please select</option>
                                                    <option value="percentage">Percent</option>
                                                </select>
                                            </div>
                                            <x-form-input wire:model="inputData.discount_amount" name="discount_amount" label="Discount amount" placeholder="Discount amount" autocomplete />
                                        </div>
                                        <hr>
                                        <x-form-checkbox-custom wire:model="inputData.hide_lead" name="hide_lead" label="Hide this lead from public jobs?" />
                                        <x-form-checkbox-custom wire:model="inputData.automate" name="automate" label="Enable tutor finding automation for this lead?" />
                                        <x-form-checkbox-custom wire:model="inputData.welcome_email" name="welcome_email" label="Send welcome email" />
                                        <x-form-checkbox-custom wire:model="inputData.vaccinated" name="vaccinated" label="Only vaccinated tutors!" />
                                        <x-form-checkbox-custom wire:model="inputData.experienced_tutor" name="experienced_tutor" label="Only experienced tutors!" />
                                        <x-form-checkbox-custom name="isSpecialRequest" label="Special request" x-model="isSpecialRequest" />
                                        <div x-show="isSpecialRequest == true" class="apply_discount" style="display: none;">
                                            <x-form-textarea wire:model="inputData.special_request_content" name="special_request_content" label="Special request content" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <ul class="list-inline wizard mb-0">
                                <li class="previous list-inline-item">
                                    <button type="button" class="btn btn-info" x-on:click="toPrevStep" :disabled="current_step == 0">Previous</button>
                                </li>
                                <li class="next list-inline-item">
                                    <button type="button" class="btn btn-info" x-on:click="toNextStep" :disabled="current_step == 4">Next</button>
                                </li>
                                <li class="finish list-inline-item" x-show="current_step == 4"><a href="javascript:;" class="btn btn-success" x-on:click="finish">Finish</a></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('create_lead_init', () => ({
            current_step: 0,
            init() {
                $('#state_id, #grade_id').on('change', function() {
                    @this.call('getSubjectsFromStateAndGrade');
                })

                $('#start_date_picker').datepicker({
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                    startDate: new Date(),
                    todayHighlight: true,
                }).on('changeDate', function(e) {
                    var selectedDate = e.format(0, 'dd/mm/yyyy');
                    @this.set('inputData.students.student1.start_date_picker', selectedDate);
                });
            },
            goStep(step) {
                this.current_step = step;
            },
            toNextStep() {
                if (this.form_validate()) this.goStep(this.current_step + 1);
            },
            toPrevStep() {
                this.goStep(this.current_step - 1);
            },
            async finish() {
                if (!this.form_validate()) return;
                await @this.call('finish');
                this.current_step = 0;
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            },
            form_validate() {
                $('#store_lead').validate({
                    rules: {
                        parent_first_name: 'required',
                        parent_last_name: 'required',
                        parent_phone: 'required',
                        parent_email: {
                            required: true,
                            email: true,
                        },
                        session_type_id: 'required',
                        state_id: 'required',
                        address: 'required',
                        suburb: 'required',
                        postcode: {
                            required: true,
                            rangelength: [4, 4]
                        },
                        student_first_name: 'required',
                        student_last_name: 'required',
                        grade_id: 'required',
                        start_date: 'required',
                        start_date_picker: 'required',
                        subject: 'required',
                        offer_amount: {
                            required: true,
                            number: true,
                        },
                        offer_valid: 'required',
                        discount_type: 'required',
                        discount_amount: {
                            required: true,
                            number: true,
                        },
                    }
                });
                if ($('#store_lead').valid()) return true;
                else return false;
            }
        }))
    })
</script>