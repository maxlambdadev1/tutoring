<div>
    @php
    $title = "Promo page Settings";
    $breadcrumbs = ["Owner", "Setting", "Promo page"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <span class="form-label fw-bold">Enable promo page &nbsp;</span>
                        <div>
                            <input type="checkbox" wire:model="promo_page_enabled" id="promo_page_enabled" name="promo_page_enabled" data-switch="success" wire:change="togglePromoPageEnabled">
                            <label for="promo_page_enabled" data-on-label="Yes" data-off-label="No"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <textarea name="option_value" class="form-control" wire:model="option_value" rows="15">{{$option_value}}</textarea>
                            <button type="button" wire:click="saveTemplate" class="btn btn-outline-info waves-effect waves-light btn-sm mt-1">Save</button>
                        </div>
                        <div class="col-md-7">
                            <div class="border border-success border-2 rounded text-center p-3">
                                {!! $option_value !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>