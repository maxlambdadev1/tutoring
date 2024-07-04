<div>
    @php
    $title = "Email and SMS Setting";
    $breadcrumbs = ["Owner", "Setting", "Email and SMS"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mb-3">Email</h4>      
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2" x-data="{showDropdown: false}">
                                <div class="input-group" id="dropdown-toggle" data-bs-toggle="dropdown">
                                    <input wire:model="email_str" id="email_str" name="email_str" wire:keydown="searchEmailByNameAndContent" x-on:focus="showDropdown = true" x-on:blur="setTimeout(() => showDropdown = false, 200)" type="text" class="form-control" placeholder="Type here to find the email (Name or Content)...">
                                </div>
                                <ul class="dropdown-menu dropdown-menu-md p-2 overflow-auto" :class="{'d-block show' : showDropdown}" style="max-height:50vh">
                                    @forelse ($email_name_lists as $email_item)
                                    <li class="cursor-pointer" wire:click="selectEmail('{{ $email_item }}')" x-on:click="showDropdown = false">
                                        <a class="dropdown-item">
                                            <div>{{ $email_item }}</div>
                                        </a>
                                    </li>
                                    @empty
                                    <li class="cursor-pointer">
                                        <a class="dropdown-item">
                                            <span>There are no emails</span>
                                        </a>
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="mb-2">
                                <label for="email_subject" class="form-label">Subject</label>
                                <input type="text" class="form-control " name="email_subject" id="email_subject" wire:model="email_subject">
                            </div>
                            <div class="mb-2">
                                <label for="email_subject" class="form-label">Content</label>
                                <textarea name="email_content" class="form-control" wire:model="email_content" rows="15">{{$email_content}}</textarea>
                            </div>
                            <button type="button" wire:click="saveEmail" class="btn btn-outline-info waves-effect waves-light btn-sm mt-1">Save</button>
                        </div>
                        <div class="col-md-6">
                            <div class="border border-1 rounded text-center">
                                {!! $email_content !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mb-3">SMS</h4>      
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2" x-data="{showDropdown1: false}">
                                <div class="input-group" id="dropdown-toggle" data-bs-toggle="dropdown">
                                    <input wire:model="sms_str" id="sms_str" name="sms_str" wire:keydown="searchSMSByNameAndContent" x-on:focus="showDropdown1 = true" x-on:blur="setTimeout(() => showDropdown1 = false, 200)" type="text" class="form-control" placeholder="Type here to find the SMS (Name or Content)...">
                                </div>
                                <ul class="dropdown-menu dropdown-menu-md p-2 overflow-auto" :class="{'d-block show' : showDropdown1}" style="max-height:50vh">
                                    @forelse ($sms_name_lists as $sms_item)
                                    <li class="cursor-pointer" wire:click="selectSMS('{{ $sms_item }}')" x-on:click="showDropdown1 = false">
                                        <a class="dropdown-item">
                                            <div>{{ $sms_item }}</div>
                                        </a>
                                    </li>
                                    @empty
                                    <li class="cursor-pointer">
                                        <a class="dropdown-item">
                                            <span>There are no SMS</span>
                                        </a>
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="mb-2">
                                <label for="sms_content" class="form-label">Content</label>
                                <textarea name="sms_content" class="form-control" wire:model="sms_content" rows="6">{{$sms_content}}</textarea>
                            </div>
                            <button type="button" wire:click="saveSMS" class="btn btn-outline-primary waves-effect waves-light btn-sm mt-1">Save</button>
                        </div>
                        <div class="col-md-6">
                            <div class="border border-1 rounded text-center p-3">
                                {!! $sms_content !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
