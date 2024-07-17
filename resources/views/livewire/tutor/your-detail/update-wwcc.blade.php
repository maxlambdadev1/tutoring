<div>
    @php
    $title = "Update WWCC Detail";
    $breadcrumbs = ["Your detail", "WWCC"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h4 class="fw-bold">WWCC detail</h4>
                            <p>Please ensure your details are upto date</p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="wwcc_application_number" class="form-label">App number</label>
                                <input type="text" class="form-control " name="wwcc_application_number" id="wwcc_application_number" wire:model="wwcc_application_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="wwcc_full_name" class="form-label">WWCC full Name</label>
                                <input type="text" class="form-control " name="wwcc_full_name" id="wwcc_full_name" wire:model="wwcc_full_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="wwcc_number" class="form-label">WWCC number</label>
                                <input type="text" class="form-control " name="wwcc_number" id="wwcc_number" wire:model="wwcc_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="wwcc_expiry" class="form-label">WWCC expiry</label>
                                <input type="text" class="form-control " name="wwcc_expiry" id="wwcc_expiry" wire:model="wwcc_expiry" value="{{$wwcc_expiry}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm" wire:click="updateWWCC">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#wwcc_expiry').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    }).on('changeDate', function(e) {
        var wwcc_expiry = e.format(0, 'dd/mm/yyyy');
        @this.set('wwcc_expiry', wwcc_expiry);
    });
</script>