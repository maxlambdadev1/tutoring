<div>
    @php
    $title = "General Settings";
    $breadcrumbs = ["Owner", "Setting", "General"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="mb-2">
                            <label for="announcement_text" class="form-label">Announcement</label>
                            <textarea class="form-control" wire:model="announcement_text" id="announcement_text" rows="5">{{$announcement_text}}</textarea>
                            <p class="text-muted">Posted by {{$announcements[0]->user->admin->admin_name ?? ''}} on {{$announcements[0]->an_date . ' ' . $announcements[0]->an_time}}</p>
                        </div>
                        <div class="text-center">
                            <input type="button" wire:click="saveAnnouncement" value="Save" class="btn btn-outline-primary waves-effect waves-light btn-sm">
                        </div>
                        <hr />
                    </div>
                    <div x-data="{isSaveTutorAnnouncementBtnClicked : @entangle('tutor_sms_flag')}">
                        <div class="mb-2">
                            <label for="tutor_sms_announcement_text" class="form-label">TUTOR ANNOUNCMENT SMS</label>
                            <textarea class="form-control" wire:model="tutor_sms_announcement_text" id="tutor_sms_announcement_text" rows="5">{{$tutor_sms_announcement_text}}</textarea>
                            <p class="text-muted mb-1">Posted by {{$announcements[1]->user->admin->admin_name ?? ''}} on {{$announcements[1]->an_date . ' ' . $announcements[1]->an_time}}</p>
                            <div class="mb-1">
                                <div class="d-inline-flex">
                                    <select name="tutor_sms_state" id="tutor_sms_state" class="form-select " wire:model="tutor_sms_state">
                                        <option value="">All States</option>
                                        @forelse ($states as $state)
                                        <option value="{{$state->name}}">{{$state->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="d-inline-flex ps-0 ps-sm-2">
                                    <div class="form-check">
                                        <input type="radio" id="tutor_sms_who1" name="tutor_sms_who1" value="1" wire:model="tutor_sms_who" class="form-check-input">
                                        <label class="form-check-label" for="tutor_sms_who1">All Tutors</label>
                                    </div>
                                </div>
                                <div class="d-inline-flex ps-0 ps-sm-2">
                                    <div class="form-check">
                                        <input type="radio" id="tutor_sms_who2" name="tutor_sms_who2" value="2" wire:model="tutor_sms_who" class="form-check-input">
                                        <label class="form-check-label" for="tutor_sms_who2">Actively Seeking Tutors</label>
                                    </div>
                                </div>
                                <div class="d-inline-flex ps-0 ps-sm-2">
                                    <div class="form-check">
                                        <input type="radio" id="tutor_sms_who3" name="tutor_sms_who3" value="3" wire:model="tutor_sms_who" class="form-check-input">
                                        <label class="form-check-label" for="tutor_sms_who3">Ignore Blocked Tutors</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <input type="button" x-show="isSaveTutorAnnouncementBtnClicked" disabled value="Processing..." class="btn btn-secondary btn-sm">
                            <input type="button" x-show="!isSaveTutorAnnouncementBtnClicked" value="Send SMS" class="btn btn-outline-primary waves-effect waves-light btn-sm" x-on:click="function() { 
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Are you sure?',
                                    showCancelButton: true,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        isSaveTutorAnnouncementBtnClicked = true;
                                        @this.call('saveTutorAnnouncementSms');
                                    }
                                })}">
                        </div>
                        <hr />
                    </div>
                    <div x-data="{isSaveParentAnnouncementBtnClicked: @entangle('parent_sms_flag')}">
                        <div class="mb-2">
                            <label for="parent_sms_announcement_text" class="form-label">PARENT ANNOUNCMENT SMS</label>
                            <textarea class="form-control" wire:model="parent_sms_announcement_text" id="parent_sms_announcement_text" rows="5">{{$parent_sms_announcement_text}}</textarea>
                            <p class="text-muted mb-1">Posted by {{$announcements[2]->user->admin->admin_name ?? ''}} on {{$announcements[2]->an_date . ' ' . $announcements[2]->an_time}}</p>
                            <div class="mb-1">
                                <div class="d-inline-flex">
                                    <select name="parent_sms_state" id="parent_sms_state" class="form-select " wire:model="parent_sms_state">
                                        <option value="">All States</option>
                                        @forelse ($states as $state)
                                        <option value="{{$state->name}}">{{$state->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="d-inline-flex ps-0 ps-sm-2">
                                    <div class="form-check">
                                        <input type="radio" id="parent_sms_who1" name="parent_sms_who1" value="1" wire:model="parent_sms_who" class="form-check-input">
                                        <label class="form-check-label" for="parent_sms_who1">All Parents</label>
                                    </div>
                                </div>
                                <div class="d-inline-flex ps-0 ps-sm-2">
                                    <div class="form-check">
                                        <input type="radio" id="parent_sms_who2" name="parent_sms_who2" value="2" wire:model="parent_sms_who" class="form-check-input">
                                        <label class="form-check-label" for="parent_sms_who2">Active Parents</label>
                                    </div>
                                </div>
                                <div class="d-inline-flex ps-0 ps-sm-2">
                                    <div class="form-check">
                                        <input type="radio" id="parent_sms_who3" name="parent_sms_who3" value="3" wire:model="parent_sms_who" class="form-check-input">
                                        <label class="form-check-label" for="parent_sms_who3">Inactive Parents</label>
                                    </div>
                                </div>
                                <div class="d-inline-flex ps-0 ps-sm-2">
                                    <div class="form-check">
                                        <input type="radio" id="parent_sms_who4" name="parent_sms_who4" value="4" wire:model="parent_sms_who" class="form-check-input">
                                        <label class="form-check-label" for="parent_sms_who4">Start of year inactive list</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <input type="button" x-show="isSaveParentAnnouncementBtnClicked" disabled value="Processing..." class="btn btn-secondary btn-sm">
                            <input type="button" x-show="!isSaveParentAnnouncementBtnClicked" value="Save SMS" class="btn btn-outline-primary waves-effect waves-light btn-sm" x-on:click="function() { 
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Are you sure?',
                                    showCancelButton: true,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        isSaveParentAnnouncementBtnClicked = true;
                                        @this.call('saveParentAnnouncementSms');
                                    }
                                })}">
                        </div>
                        <hr />
                    </div>
                    <div class="d-flex mb-3">
                        <span class="form-label fw-bold">Job Disable/Enable &nbsp;</span>
                        <div>
                            <input type="checkbox" wire:model="job_switch" id="job_switch" name="job_switch" data-switch="success" wire:change="changeJobSwitch">
                            <label for="job_switch" data-on-label="Yes" data-off-label="No"></label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-md-5">
                            <div class="input-group">
                                <span class="input-group-text"> Online lesson limitation</span>
                                <input type="number" class="form-control" name="online_limit" id="online_limit" wire:model="online_limit">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" wire:click="saveOnlineLessonLimit" class="btn btn-outline-info waves-effect waves-light my-1 my-md-0">Save</button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-md-5">
                            <div class="input-group">
                                <span class="input-group-text"> Experienced tutor limitation</span>
                                <input type="number" class="form-control" name="experience_limit" id="experience_limit" wire:model="experience_limit">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" wire:click="saveExperiencedTutorLimit" class="btn btn-primary  my-1 my-md-0">Save</button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-md-3 py-1">
                            <div class="input-group">
                                <span class="input-group-text">Bookings</span>
                                <input type="number" class="form-control" name="bookings" id="bookings" wire:model="daily_target.booking">
                            </div>
                        </div>
                        <div class="col-12 col-md-3 py-1">
                            <div class="input-group">
                                <span class="input-group-text">Conversions</span>
                                <input type="number" class="form-control" name="conversion" id="conversion" wire:model="daily_target.conversion">
                            </div>
                        </div>
                        <div class="col-12 col-md-3 py-1">
                            <div class="input-group">
                                <span class="input-group-text">1st sessions</span>
                                <input type="number" class="form-control" name="first_session" id="first_session" wire:model="daily_target.first_session">
                            </div>
                        </div>
                        <div class="col-md-2 py-1">
                            <button type="button" wire:click="saveDailyTarget" class="btn btn-info ">Save</button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-md-3 py-1">
                            <div class="input-group">
                                <span class="input-group-text">F2F Cron (minutes)</span>
                                <input type="number" class="form-control" name="f2f_cron" id="f2f_cron" wire:model="cron_time.f2f_cron">
                            </div>
                        </div>
                        <div class="col-12 col-md-3 py-1">
                            <div class="input-group">
                                <span class="input-group-text">Online Cron (minutes)</span>
                                <input type="number" class="form-control" name="number_tutors_online" id="number_tutors_online" wire:model="cron_time.number_tutors_online">
                            </div>
                        </div>
                        <div class="col-12 col-md-3 py-1">
                            <div class="input-group">
                                <span class="input-group-text">Number of tutors</span>
                                <input type="number" class="form-control" name="online_cron" id="online_cron" wire:model="cron_time.online_cron">
                            </div>
                        </div>
                        <div class="col-md-2 py-1">
                            <button type="button" wire:click="saveCronTime" class="btn btn-success ">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>