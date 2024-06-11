<div>
    <div id="tutorDetailModal{{$tutor->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center w-100" id="myModalLabel">
                        <div class="mb-2">
                            <img src="{{ $tutor->photo ?? "/images/no_avatar.jpg"}}" width="60" height="60" class="border border-1 rounded-circle" />
                        </div>
                        <div class="fw-bold">{{$tutor->tutor_name}}</div>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                        @if ($tutor->vaccinated)
                        <div class="alert alert-success" role="alert">
                            I am fully vaccinated against Covid 19!
                        </div>
                        @else
                        <div class="alert alert-danger" role="alert">
                            I am not vaccinated against Covid 19!
                        </div>
                        @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tutor_email{{$tutor->id}}" class="form-label">Email</label>
                            <input type="text" class="form-control " name="tutor_email" id="tutor_email{{$tutor->id}}" value="{{$tutor->tutor_email}}" wire:model="tutor_email" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tutor_phone{{$tutor->id}}" class="form-label">Phone</label>
                            <input type="text" class="form-control " name="tutor_phone" id="tutor_phone{{$tutor->id}}" value="{{$tutor->tutor_phone}}" wire:model="tutor_phone" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tutor_id{{$tutor->id}}" class="form-label">Tutor ID</label>
                            <input type="text" class="form-control " disable name="tutor_id" id="tutor_id{{$tutor->id}}" value="{{$tutor->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="birthday{{$tutor->id}}" class="form-label">Birthday</label>
                            <input type="text" class="form-control " name="birthday" id="birthday{{$tutor->id}}" value="{{$tutor->birthday}}" wire:model="birthday" >
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-6 mb-3">
                            <label for="state{{$tutor->id}}" class="form-label">State</label>
                            <select name="state" id="state{{$tutor->id}}" class="form-select" wire:model="state" >
                                <option value="">Please select...</option>
                                @foreach($states as $item)
                                <option value="{{ $item->name }}" @if ($item->name == $state) 'selected' @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address{{$tutor->id}}" class="form-label">Address</label>
                            <input type="text" class="form-control " name="address" id="address{{$tutor->id}}" value="{{$tutor->address}}" wire:model="address" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="suburb" class="form-label">Suburb</label>
                            <input type="text" class="form-control " name="suburb" id="suburb{{$tutor->id}}" wire:model="suburb" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="postcode" class="form-label">Postcode</label>
                            <input type="text" class="form-control " name="postcode" id="postcode{{$tutor->id}}" wire:model="postcode">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender{{$tutor->id}}" class="form-select" wire:model="gender">
                                <option value=""  @if ($gender != 'Male' && $gender != 'Female') 'selected' @endif>Any</option>
                                <option value="Male" @if ($gender == 'Male') 'selected' @endif>Male</option>
                                <option value="Female" @if ($gender == 'Female') 'selected' @endif>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="expert_sub" class="form-label">Subjects</label>
                            <p class="mb-0">{{$tutor->expert_sub}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="availabilities" class="form-label">Availability</label>
                            <p class="mb-0" style="max-height: 100px; overflow:auto;">{{implode(', ', $tutor->availabilities1)}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ABN" class="form-label">ABN</label>
                            <input type="text" class="form-control " name="ABN" id="ABN{{$tutor->id}}" wire:model="ABN" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bank_account_name" class="form-label">Bank account name</label>
                            <input type="text" class="form-control " name="bank_account_name" id="bank_account_name{{$tutor->id}}" wire:model="bank_account_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bank_account_number" class="form-label">Bank account number</label>
                            <input type="text" class="form-control " name="bank_account_number" id="bank_account_number{{$tutor->id}}" wire:model="bank_account_number" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bsb" class="form-label">BSB</label>
                            <input type="text" class="form-control " name="bsb" id="bsb{{$tutor->id}}" wire:model="bsb">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="have_wwcc" class="form-label">Have WWCC</label>
                            <p class="mb-0">{!! $tutor->have_wwcc ? '<span class="badge bg-primary">Yes</span>' : '<span class="badge bg-danger">No</span>' !!}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="wwcc_application_number" class="form-label">WWCC Application number</label>
                            <input type="text" class="form-control " name="wwcc_application_number" id="wwcc_application_number{{$tutor->id}}" wire:model="wwcc_application_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="wwcc_fullname" class="form-label">WWCC Name</label>
                            <input type="text" class="form-control " name="wwcc_fullname" id="wwcc_fullname{{$tutor->id}}" wire:model="wwcc_fullname" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="wwcc_expiry" class="form-label">WWCC Expiry</label>
                            <input type="text" class="form-control " name="wwcc_expiry" id="wwcc_expiry{{$tutor->id}}" wire:model="wwcc_expiry">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="wwcc_number" class="form-label">WWCC Number</label>
                            <input type="text" class="form-control " name="wwcc_number" id="wwcc_number{{$tutor->id}}" wire:model="wwcc_number">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Verified by</label>
                            <p class="mb-0">{{$tutor->wwcc->verified_by ?? '-'}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Verified on</label>
                            <p class="mb-0">{{$tutor->wwcc->verified_on ?? '-'}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="online_url" class="form-label">Online URL</label>
                            <input type="text" class="form-control " name="online_url" id="online_url{{$tutor->id}}" wire:model="online_url">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Onboarding</label>
                            <p class="mb-0">{{$tutor->onboarding == 1 ? 'Completed' : 'Not completed'}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Verified email status</label>
                            <p class="mb-0">{!! $tutor->logged_status == 1 ? '<span class="badge bg-primary">Yes</span>' : '<span class="badge bg-danger">No</span>'!!}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Signature</label>
                            <div class="">
                            @if (!empty($tutor->signature))
                            <a href="https://alchemy.team/{{ urlencode($tutor->signature)}}">View</a>
                            @else
                            -
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Tutor ID &nbsp;</label>
                            @if (!empty($tutor->id_photo))
                            <a href="https://alchemy.team/{{ urlencode($tutor->id_photo)}}">View</a>
                            @else
                            -
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="saveTutorDetails" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>