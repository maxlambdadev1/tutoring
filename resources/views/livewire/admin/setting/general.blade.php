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
                                        <option value="{{$state->name}}" >{{$state->name}}</option>
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
                            <input type="button" x-show="!isSaveTutorAnnouncementBtnClicked" value="Save" class="btn btn-outline-primary waves-effect waves-light btn-sm" x-on:click="function() { 
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
                                        <option value="{{$state->name}}" >{{$state->name}}</option>
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
                            <input type="button" x-show="!isSaveParentAnnouncementBtnClicked" value="Save" class="btn btn-outline-primary waves-effect waves-light btn-sm" x-on:click="function() { 
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
                </div>
            </div>
        </div>
    </div>

</div>