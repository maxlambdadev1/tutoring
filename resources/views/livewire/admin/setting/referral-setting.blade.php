<div>
    @php
    $title = "Referral Settings";
    $breadcrumbs = ["Owner", "Setting", "Referral"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="referral_amount" class="form-label">Referral amount</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="basic-addon1">$</span>
                                    <input type="text" class="form-control " name="referral_amount" id="referral_amount" wire:model="referral_amount" aria-label="referral_amount" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="referral_special_amount" class="form-label">Special referral amount</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="basic-addon2">$</span>
                                    <input type="text" class="form-control " name="referral_special_amount" id="referral_special_amount" wire:model="referral_special_amount" aria-label="referral_special_amount" aria-describedby="basic-addon2">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="referral_recruiter_amount" class="form-label">Recruiter referral amount</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="basic-addon3">$</span>
                                    <input type="text" class="form-control " name="referral_recruiter_amount" id="referral_recruiter_amount" wire:model="referral_recruiter_amount" aria-label="referral_recruiter_amount" aria-describedby="basic-addon3">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-outline-info waves-effect waves-light btn-sm" wire:click="saveReferralSetting">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>