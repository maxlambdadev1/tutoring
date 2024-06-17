<div>
    @php
    $title = "Verify WWCC";
    $breadcrumbs = ["WWCC", "Verify"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <x-session-alert />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tutor_id" class="form-label">Unverified tutors</label>
                                <select name="tutor_id" id="tutor_id" class="form-select " wire:model="tutor_id" wire:change="selectTutor">
                                    @if (!empty($unverified_tutors))
                                    <option value="">Select tutors</option>
                                    @foreach($unverified_tutors as $tutor1)
                                    <option value="{{$tutor1->id}}">{{$tutor1->tutor_name}}({{$tutor1->tutor_email}})</option>
                                    @endforeach
                                    @else
                                    <option value="">There are no unverified tutors</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    @if (!empty($tutor))
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-5 fw-bold">State</div>
                                <div class="col-md-7">{{$tutor->state}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-5 fw-bold">WWCC Fullname</div>
                                <div class="col-md-7">{{$tutor->wwcc_fullname}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-5 fw-bold">WWCC Number</div>
                                <div class="col-md-7">{{$tutor->wwcc_number}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-5 fw-bold">WWCC Expiry</div>
                                <div class="col-md-7">{{$tutor->wwcc_expiry}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-5 fw-bold">Date of birth</div>
                                <div class="col-md-7">{{$tutor->birthday}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-5 fw-bold">Last verified on</div>
                                <div class="col-md-7">{{$tutor->wwcc_verified_on ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-5 fw-bold">Last verified by</div>
                                <div class="col-md-7">{{$tutor->wwcc_verified_by ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-5 fw-bold">Block/Unblock from jobs</div>
                                <div class="col-md-7">
                                    <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm" wire:click="blockOrUnblockFromJobs1({{$tutor->id}})">
                                        {{ $tutor->accept_job_status == 1 ? 'Block from jobs' : 'Unblock from jobs'}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-outline-success waves-effect waves-light btn-sm" wire:click="verifyWWCC1({{$tutor->id}})">Verify WWCC</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>