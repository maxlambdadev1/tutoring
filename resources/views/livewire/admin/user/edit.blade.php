<div>
    @php
    $title = "Edit a member";
    $breadcrumbs = ["Owner", "Edit"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-session-alert />
                    <div class="text-center">
                        <img src="{{asset($admin->getPhoto())}}" alt="Profile" width="150" class="border border-1 rounded-circle">
                    </div>
                    <hr>
                    <form wire:submit.prevent="save" class="needs-validation">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <x-form-input wire:model="email" type="email" name="email" :value="$email" label="Email" placeholder="Email" autocomplete="email" autofocus />
                                <x-form-input wire:model="first_name" type="text" name="first_name" :value="$first_name" label="First Name" placeholder="First Name" autocomplete="first_name" />
                                <x-form-input wire:model="last_name" type="text" name="last_name" :value="$last_name" label="Last name" placeholder="Last name" autocomplete="last_name" />
                                <x-form-input wire:model="phone" type="text" name="phone" label="Phone" :value="$phone" placeholder="Phone" autocomplete="phone" />
                            </div>
                            <div class="col-md-6">
                                <x-form-select wire:model="admin_role_id" name="admin_role_id" label="Role" :items="$admin_roles" :selectedValue="$admin->admin_role_id"/>
                                <x-form-input wire:model="photo" type="file" name="photo" label="Profile Photo" />
                                @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="col-md-2">
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
