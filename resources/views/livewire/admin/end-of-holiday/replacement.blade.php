<div>
    @php
    $title = "End of holiday student repalcement list";
    $breadcrumbs = ["End of holiday", "Replacement list"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body" x-data="{isNewYearReplacementSmsBtnClicked : false}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d-flex mb-1">
                                <span class="form-label fw-bold">Offer a replacement tutor &nbsp;</span>
                                <div>
                                    <input type="checkbox" id="replacement_status_switch" name="replacement_status_switch" data-switch="success" {{ !empty($replacement_status_switch_option->option_value) ? 'checked' : ''}} wire:click="changeReplacementStatus()">
                                    <label for="replacement_status_switch" data-on-label="Yes" data-off-label="No"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="button" wire:click="newYearReplacementSms" value="Send SMS" class="btn btn-info waves-effect waves-light btn-sm" :disabled="isNewYearReplacementSmsBtnClicked" x-on:click="isNewYearReplacementSmsBtnClicked = true">
                        </div>
                    </div>
                    <livewire:admin.end-of-holiday.new-year-replacement-table />
                </div>
            </div>
        </div>
    </div>
</div>