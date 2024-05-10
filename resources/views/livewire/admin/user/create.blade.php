<div>
    @php
    $title = "Register new member";
    $breadcrumbs = ["Owner", "Register"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-session-alert />
                    <form wire:submit.prevent="create" class="needs-validation">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <x-form-input wire:model="email" type="email" name="email" label="Email" placeholder="Email" autocomplete="email" autofocus />
                                <x-form-input wire:model="first_name" type="text" name="first_name" label="First Name" placeholder="First Name" autocomplete="first_name" />
                                <x-form-input wire:model="last_name" type="text" name="last_name" label="Last name" placeholder="Last name" autocomplete="last_name" />
                                <x-form-input wire:model="phone" type="text" name="phone" label="Phone" placeholder="Phone" autocomplete="phone" />
                            </div>
                            <div class="col-md-6">
                                <x-form-password wire:model="password" name="password" id="password" label="Password" />
                                <x-form-password wire:model="password_confirmation" name="password_confirmation" id="password_confirmation" label="Confirm Password" />
                                <x-form-select wire:model="admin_role_id" name="admin_role_id" label="Role" :items="$admin_roles" />
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