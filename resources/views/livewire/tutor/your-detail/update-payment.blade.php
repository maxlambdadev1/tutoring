<div>
    @php
    $title = "Update Payment Detail";
    $breadcrumbs = ["Your detail", "Payment"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h4 class="fw-bold">Payment detail</h4>
                            <p>Please ensure your details are upto date</p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="abn" class="form-label">ABN</label>
                                <input type="text" class="form-control " name="abn" id="abn" wire:model="abn">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bank_account_name" class="form-label">Account Name</label>
                                <input type="text" class="form-control " name="bank_account_name" id="bank_account_name" wire:model="bank_account_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bsb" class="form-label">BSB</label>
                                <input type="text" class="form-control " name="bsb" id="bsb" wire:model="bsb">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bank_account_number" class="form-label">Account number</label>
                                <input type="text" class="form-control " name="bank_account_number" id="bank_account_number" wire:model="bank_account_number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm" wire:click="updatePaymentInfo">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row" class="mb-3" x-data="{id_type: 1}">
                        <div class="col-md-12 mb-3">
                            <h4 class="fw-bold mb-3">Your ID</h4>
                            <div class="fw-bold mb-1">Your identification:</div>
                            <ul class="ms-3" style="list-style-type: disc;">
                                <li>Must be in full color (no black and white photos or scans)</li>
                                <li>Should clearly include all details and be large enough to read</li>
                                <li>Must be valid and not expired</li>
                                <li>The name and date of birth on your ID must match your details as entered into our system</li>
                                <li>If drivers license or photo card, include front and back in separate images</li>
                                <li>If passport, should display the entire photo page</li>
                            </ul>
                            <div>Failure to upload identification that meets the above criteria may result in payment delays or blocks.</div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_type" class="form-label">Please select acceptable forms of identification</label>
                                <select name="id_type" id="id_type" class="form-select " wire:model="id_type" x-on:change="id_type = $event.target.value">
                                    <option value="1">Passport</option>
                                    <option value="2">Driver Licence</option>
                                    <option value="3">Photo Card</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <x-form-input wire:model="photo_front" type="file" name="photo_front" label="Front Photo" />
                            @if ($photo_front)
                            <img src="{{ $photo_front->temporaryUrl() }}" class="col-md-2">
                            @endif
                        </div>
                        <div class="col-md-6" x-show="id_type != 1">
                            <x-form-input wire:model="photo_back" type="file" name="photo_back" label="Back Photo" />
                            @if ($photo_back)
                            <img src="{{ $photo_back->temporaryUrl() }}" class="col-md-2">
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm" wire:click="updateIDImage">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>