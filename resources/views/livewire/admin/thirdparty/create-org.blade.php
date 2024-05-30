<div>
    @php
    $title = "Register new organisation";
    $breadcrumbs = ["Thirdparty", "Create"];
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
                                <x-form-input wire:model="organisation_name" type="text" name="organisation_name" label="Organisation name" />
                                <x-form-input wire:model="primary_contact_first_name" type="text" name="primary_contact_first_name" label="Primary contact first name" />
                                <x-form-input wire:model="primary_contact_last_name" type="text" name="primary_contact_last_name" label="Primary contact last name" />
                                <x-form-input wire:model="primary_contact_role" type="text" name="primary_contact_role" label="Primary contact role" />
                            </div>
                            <div class="col-md-6">
                                <x-form-input wire:model="primary_contact_phone" name="primary_contact_phone" id="primary_contact_phone" label="Primary contact phone number" />
                                <x-form-input wire:model="primary_contact_email" name="primary_contact_email" id="primary_contact_email" label="Primary contact email address" />
                                <x-form-input wire:model="email_for_billing" name="email_for_billing" label="Email for billing" />
                                <x-form-input wire:model="email_for_confirmations" type="text" name="email_for_confirmations" label="Email for confirmations" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <x-form-textarea wire:model="comment" name="comment" label="Comments for organisation" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" wire:click="viewOrganisation" class="btn btn-success waves-effect waves-light">View organisations</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>