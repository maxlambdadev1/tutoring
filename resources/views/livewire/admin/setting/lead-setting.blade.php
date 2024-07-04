<div>
    @php
    $title = "Lead Settings";
    $breadcrumbs = ["Owner", "Setting", "Lead"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mb-2">Hot lead setting</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="offer_amount" class="form-label">Offer ammount</label>
                                <input type="text" class="form-control " name="offer_amount" id="offer_amount" wire:model="offer_amount">
                            </div>
                            <div class="mb-2">
                                <label for="valid_until" class="form-label">Valid until</label>
                                <input type="text" class="form-control " name="valid_until" id="valid_until" value="{{$valid_until}}">
                            </div>
                            <div class="mb-2">
                                <div class="d-inline-flex">
                                    <div class="form-check">
                                        <input type="radio" id="lead_type1" value="1" wire:model="lead_type" class="form-check-input">
                                        <label class="form-check-label" for="lead_type1">F2F only</label>
                                    </div>
                                </div>
                                <div class="d-inline-flex ps-0 ps-sm-2">
                                    <div class="form-check">
                                        <input type="radio" id="lead_type2" value="2" wire:model="lead_type" class="form-check-input">
                                        <label class="form-check-label" for="lead_type2">Online only</label>
                                    </div>
                                </div>
                                <div class="d-inline-flex ps-0 ps-sm-2">
                                    <div class="form-check">
                                        <input type="radio" id="lead_type3" value="" wire:model="lead_type" class="form-check-input">
                                        <label class="form-check-label" for="lead_type3">All</label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-outline-info waves-effect waves-light btn-sm" wire:click="saveHotLeadSetting">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-2">Lead age setting</h4>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="text-center mb-1 fw-bold">First step</div>
                            <div class="mb-2">
                                <label for="step1_time" class="form-label">Age(hours)</label>
                                <input type="text" class="form-control " name="step1_time" id="step1_time" wire:model="step1_time">
                            </div>
                            <div class="mb-2">
                                <label for="step1_amount" class="form-label">Offer(AUD)</label>
                                <input type="text" class="form-control " name="step1_amount" id="step1_amount" wire:model="step1_amount">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="text-center mb-1 fw-bold">Second step</div>
                            <div class="mb-2">
                                <label for="step2_time" class="form-label">Age(hours)</label>
                                <input type="text" class="form-control " name="step2_time" id="step2_time" wire:model="step2_time">
                            </div>
                            <div class="mb-2">
                                <label for="step2_amount" class="form-label">Offer(AUD)</label>
                                <input type="text" class="form-control " name="step2_amount" id="step2_amount" wire:model="step2_amount">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-info waves-effect waves-light btn-sm" wire:click="saveLeadAgeSetting">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#valid_until').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    }).on('changeDate', function(e) {
        let date = e.format(0, 'dd/mm/yyyy');
        @this.set('valid_until', date);
    });
</script>