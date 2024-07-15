<div>
    @php
    $title = "Update detail";
    $breadcrumbs = ["Your detail", "Update"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First name</label>
                                <input type="text" class="form-control " name="first_name" id="first_name" wire:model="first_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last name</label>
                                <input type="text" class="form-control " name="last_name" id="last_name" wire:model="last_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tutor_email" class="form-label">Email</label>
                                <input type="text" class="form-control " name="tutor_email" id="tutor_email" wire:model="tutor_email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tutor_phone" class="form-label">Phone</label>
                                <input type="text" class="form-control " name="tutor_phone" id="tutor_phone" wire:model="tutor_phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Date of birth</label>
                                <input type="text" class="form-control " name="birthday" id="birthday" value="{{$birthday}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select " wire:model="gender" >
                                    <option value="">Please select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control " name="address" id="address" wire:model="address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="suburb" class="form-label">Suburb</label>
                                <input type="text" class="form-control " name="suburb" id="suburb" wire:model="suburb">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="postcode" class="form-label">Postcode</label>
                                <input type="text" class="form-control " name="postcode" id="postcode" wire:model="postcode">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                        <button type="button" class="btn btn-info waves-effect waves-light btn-sm" wire:click="updateDetail">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#birthday').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    }).on('changeDate', function(e) {
        var birthday = e.format(0, 'dd/mm/yyyy');
        @this.set('birthday', birthday);
    });
</script>