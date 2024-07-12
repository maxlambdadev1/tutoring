<div>
    @php
    $title = "Payments";
    $breadcrumbs = ["Payments"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Download Financial Year Summary</h5>
                    <div class="row">
                        <div class="col-md-2 my-1">
                            <input type="text" class="form-control " name="financial_year" id="financial_year" x-ref="financial_year">
                        </div>
                        <div class="col-md-6 my-1"> <button type="button" class="btn btn-outline-info waves-effect waves-light" wire:click="getFinancialDetails($refs.financial_year.value)">Get earnings</button>
                        </div>
                    </div>
                    <livewire:tutor.payments-table />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#financial_year').datepicker({
        autoclose: true,
        format: 'yyyy',
        viewMode: 'years',
        minViewMode: 'years',
        todayHighlight: true,
    });
</script>