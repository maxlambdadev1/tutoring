<div>
    @php
    $title = "Price Audit";
    $breadcrumbs = ["Payments", "Price audit"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body" x-data="{ init() { 
                    $('#start_date').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            todayHighlight: true,
                        });
                    $('#end_date').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            todayHighlight: true,
                        });
                    } }">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start date</label>
                                <input type="text" class="form-control " name="start_date" id="start_date" x-ref="start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End date</label>
                                <input type="text" class="form-control " name="end_date" id="end_date" x-ref="end_date">
                            </div>
                        </div>
                    </div>
                    <div class="row" x-data="{isLoading : @entangle('isLoading') }">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm" 
                                x-on:click="isLoading = true" x-show="isLoading == false"
                                wire:click="generateAudit($refs.start_date.value, $refs.end_date.value)">
                                Generate audit
                            </button>
                            <div class="spinner-border text-success" role="status" x-show="isLoading == true"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>