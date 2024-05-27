<div>
    <div id="editLeadModal{{$job->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Edit lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <label class="col-5 form-label">Session date</label>
                        <div class="col-7 text-center">
                            @forelse ($job->availabilities1 as $item)
                            <div>{{$item}}</div>
                            @empty
                            There are no availabilities
                            @endforelse
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        @if (!!$job->automation)
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateAvailabilitiesModal{{$job->id}}">Edit time</button>
                        @else
                        <div class="alert alert-danger text-center">
                            <div>The automation is currently running, you can't edit availabilities.</div>
                            <button class="btn btn-info btn-sm" x-on:click="function() { 
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Stop automation',
                                    text: 'This will stop the automation and all matching tutors will be contacted upon restarting. Are you sure?',
                                    showCancelButton: true,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $('#editLeadModal{{$job->id}}').modal('hide');
                                        @this.call('stopAutomation', {{$job->id}});
                                    }
                                })}">Stop automation</button>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date_picker" class="form-label">Start date</label>
                            <input type="text" class="form-control " name="start_date_picker" id="start_date_picker{{$job->id}}" value="{{$job->start_date}}" wire:model="start_date" autocomplete="start_date_picker" x-ref="start_date_picker{{$job->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="session_type_id" class="form-label">Session Type</label>
                            <select name="session_type_id" id="session_type_id" class="form-select" wire:model="session_type_id">
                                <option value="">Please select...</option>
                                @foreach($session_types as $item)
                                <option value="{{ $item->id }}" @if($item->id == $job->session_type_id) selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" x-data="{stateId : {{ $state_id }}}">
                        <div class="col-md-6 mb-3">
                            <label for="state_id" class="form-label">State</label>
                            <select name="state_id" id="state_id" class="form-select" wire:model="state_id" x-on:change="stateId = $event.target.value">
                                <option value="">Please select...</option>
                                @foreach($states as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <select name="subject" id="subject" class="form-select" wire:model="subject">
                                <option value="">Please select...</option>
                                @foreach($subjects as $item)
                                <option value="{{ $item->name }}" x-show="stateId == {{$item->state_id}}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="suburb" class="form-label">Session location</label>
                            <input type="text" class="form-control " name="suburb" id="suburb" wire:model="suburb" placeholder="Suburb" autocomplete="suburb">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label">Prefered gender</label>
                            <select name="prefered_gender" id="prefered_gender" class="form-select" wire:model="prefered_gender">
                                <option value="">Any</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="job_notes" class="form-label">Session notes</label>
                            <textarea class="form-control " name="job_notes" id="job_notes" rows="3" wire:model="job_notes"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" x-data="{tutorApplyOffer : {{!empty($job->job_offer) ? 'true' : 'false'}}}">
                            <x-form-checkbox-custom wire:model="tutor_apply_offer" name="tutor_apply_offer" label="Apply tutor offer" x-model="tutorApplyOffer" />
                            <div x-show="tutorApplyOffer == true" class="apply_offer" style="display: none;">
                                <x-form-select-origin wire:model="offer_type" name="offer_type" label="Offer type" :items="['Fixed','Percent']" />
                                <x-form-input wire:model="offer_amount" name="offer_amount" label="Offer amount" />
                                <x-form-select-origin wire:model="offer_valid" name="offer_valid" label="Valid until" :items="$offer_valid_list" />
                            </div>
                        </div>
                        <div class="col-md-6" x-data="{parentApplyDiscount : {{!empty($job->parent->price_parent_discount) ? 'true' : 'false'}}}">
                            <x-form-checkbox-custom wire:model="parent_apply_discount" name="parent_apply_discount" label="Apply parent discount" x-model="parentApplyDiscount" />
                            <div x-show="parentApplyDiscount == true" class="apply_discount" style="display: none;">
                                <x-form-select-origin wire:model="discount_type" name="discount_type" label="Discount type" :items="['Fixed','Percent']" />
                                <x-form-input wire:model="discount_amount" name="discount_amount" label="Discount amount" placeholder="Discount amount" autocomplete />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <x-form-checkbox-custom wire:model="experienced_tutor" name="experienced_tutor" label="Only experienced tutors!" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer" x-data="{ init() { 
                    $('#start_date_picker{{$job->id}}').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            startDate: new Date(),
                            todayHighlight: true,
                        });
                    } }">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="editLead1({{$job->id}}, $refs.start_date_picker{{$job->id}}.value)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div id="updateAvailabilitiesModal{{$job->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Update Availabilities</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="accordion" id="accordionExample{{$job->id}}">
                                @php $index = 0; @endphp
                                @foreach ($total_availabilities as $item)
                                <div class="accordion-item">
                                    <h2 class="accordion-header mt-0" id="heading{{$item->name}}">
                                        <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$item->name}}" aria-expanded="false" aria-controls="collapse{{$item->name}}">
                                            {{$item->name}}
                                        </button>
                                    </h2>
                                    <div id="collapse{{$item->name}}" class="accordion-collapse collapse" aria-labelledby="heading{{$item->name}}" data-bs-parent="accordionExample{{$job->id}}">
                                        @foreach ($item->getAvailabilitiesName() as $ele)
                                        @php $avail_hour = $item->short_name . '-' . $ele; $index++; @endphp
                                        <div class="avail_hours w-100 @if(in_array($avail_hour, $job->availabilities)) active @endif {{$index}}" data-value="{{$avail_hour}}" x-on:click="$('#updateAvailabilitiesModal{{$job->id}} .avail_hours.{{$index}}').toggleClass('active')">
                                            {{$ele}}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="updateAvailabilities({{$job->id}}, $('#updateAvailabilitiesModal{{$job->id}} .avail_hours.active').map(function(index, item) { return $(item).attr('data-value')}).get())" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>