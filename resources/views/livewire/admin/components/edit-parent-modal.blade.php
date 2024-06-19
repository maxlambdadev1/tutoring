<div>
    <div id="updateParentDetailModal{{$parent->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Update parent information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input wire:model="parent_name" name="parent_name" label="Parent name" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input wire:model="parent_email" name="parent_email" label="Parent email" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input wire:model="parent_cc" name="parent_cc" label="Parent CC" />
                        </div>
                        <div class="col-md-6">
                            <x-form-checkbox-custom wire:model="manual_payer" name="manual_payer{{$parent->id}}" label="Manual payer" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input wire:model="parent_phone" name="parent_phone" label="Parent phone" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input wire:model="parent_address" name="parent_address" label="Parent address" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input wire:model="parent_suburb" name="parent_suburb" label="Parent suburb" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input wire:model="parent_postcode" name="parent_postcode" label="Parent postcode" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input wire:model="parent_price" name="parent_price" label="Parent price" disabled/>
                        </div>
                        <div class="col-md-6" x-data="{parentApplyDiscount : {{!empty($parent->price_parent_discount) ? 'true' : 'false'}}}">
                            <x-form-checkbox-custom wire:model="parent_apply_discount" name="parent_apply_discount{{$parent->id}}" label="Apply parent discount" x-model="parentApplyDiscount" />
                            <div x-show="parentApplyDiscount == true" class="apply_discount" >
                                <div class="mb-3">
                                    <label for="discount_type{{$parent->id}}" class="form-label">Discount type</label>
                                    <select class="form-select" wire:model="discount_type" id="discount_type{{$parent->id}}">
                                        <option value="">Please select</option>
                                        <option value="percentage" >Percent</option>
                                    </select>
                                </div>
                                <x-form-input wire:model="discount_amount" name="discount_amount" label="Discount amount" placeholder="Discount amount" autocomplete />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="thirdparty_org_id{{$parent->id}}" class="form-label">Organisation</label>
                                <select class="form-select" wire:model="thirdparty_org_id" id="thirdparty_org_id">
                                    <option value="">No organisation</option>
                                    @foreach($organisations as $org)
                                    <option value="{{$org->id}}" >{{$org->organisation_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="updateParentDetail" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>
