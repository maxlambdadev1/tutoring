<div>
    @section('title')
    Reference require // Alchemy Tuition
    @endsection
    @section('description')
    @endsection

    <div class="" style="height:100vh;" x-data="reference_require_init">
        <div class="container">
            <div class="row mt-4 mt-md-5">
                <div class="col-12 mb-4">
                    <form action="#" id="tutor_character_refer_form">
                        <h2 class="mb-4">Character References:</h2>
                        <p class="">As an additional security measure for the families we work with, we ask you to provide two character references that can verify your suitability for the role.</p>
                        <p class="">These are not professional references - they do not have to be people you have worked with. They can be friends, family members or previous teachers; anyone that can verify you have the qualities we look for in a tutor.</p>
                        <p class="mb-3"><a href="/character-references" target="_blank">You can learn more about our character references here.</a></p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tutor_application_reference_fname1" class="form-label fw-bold">Reference 1 First Name <span style="color: red;">*</span></label>
                                <input type="text" name="tutor_application_reference_fname1" class="form-control" id="tutor_application_reference_fname1" wire:model="tutor_application_reference_fname1" x-model="tutor_application_reference_fname1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tutor_application_reference_lname1" class="form-label fw-bold">Reference 1 Last Name <span style="color: red;">*</span></label>
                                <input type="text" name="tutor_application_reference_lname1" class="form-control" id="tutor_application_reference_lname1" wire:model="tutor_application_reference_lname1" x-model="tutor_application_reference_lname1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tutor_application_reference_email1" class="form-label fw-bold">Reference 1 Email Address <span style="color: red;">*</span></label>
                                <input type="text" name="tutor_application_reference_email1" class="form-control" id="tutor_application_reference_email1" wire:model="tutor_application_reference_email1" x-model="tutor_application_reference_email1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tutor_application_reference_relationship1" class="form-label fw-bold">Reference 1 Relationship <span style="color: red;">*</span></label>
                                <select name="tutor_application_reference_relationship1" id="tutor_application_reference_relationship1"
                                    wire:model="tutor_application_reference_relationship1" x-model="tutor_application_reference_relationship1" class="form-control form-select">
                                    <option value=""></option>
                                    <option value="Family member">Family member</option>
                                    <option value="Friend">Friend</option>
                                    <option value="Former teacher">Former teacher</option>
                                    <option value="Employer or colleague">Employer or colleague</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            @if ($number == 2)
                            <div class="col-md-6 mb-3">
                                <label for="tutor_application_reference_fname2" class="form-label fw-bold">Reference 2 First Name <span style="color: red;">*</span></label>
                                <input type="text" name="tutor_application_reference_fname2" class="form-control" id="tutor_application_reference_fname2" wire:model="tutor_application_reference_fname2" x-model="tutor_application_reference_fname2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tutor_application_reference_lname2" class="form-label fw-bold">Reference 2 Last Name <span style="color: red;">*</span></label>
                                <input type="text" name="tutor_application_reference_lname2" class="form-control" id="tutor_application_reference_lname2" wire:model="tutor_application_reference_lname2" x-model="tutor_application_reference_lname2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tutor_application_reference_email2" class="form-label fw-bold">Reference 2 Email Address <span style="color: red;">*</span></label>
                                <input type="text" name="tutor_application_reference_email2" class="form-control" id="tutor_application_reference_email2" wire:model="tutor_application_reference_email2" x-model="tutor_application_reference_email2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tutor_application_reference_relationship2" class="form-label fw-bold">Reference 2 Relationship <span style="color: red;">*</span></label>
                                <select name="tutor_application_reference_relationship2" id="tutor_application_reference_relationship2"
                                    wire:model="tutor_application_reference_relationship2" x-model="tutor_application_reference_relationship2" class="form-control form-select">
                                    <option value=""></option>
                                    <option value="Family member">Family member</option>
                                    <option value="Friend">Friend</option>
                                    <option value="Former teacher">Former teacher</option>
                                    <option value="Employer or colleague">Employer or colleague</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            @endif
                        </div>
                        <div class="mt-3 text-center">
                            <button type="button" class="btn btn-warning text-white mb-2" x-on:click="submit" x-show="!loading">Submit</button>
                            <button type="button" class="btn btn-warning text-white mb-2" x-show="loading" disabled>
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Submitting...</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reference_require_init', () => ({
            tutor_application_reference_fname1: '',
            tutor_application_reference_lname1: '',
            tutor_application_reference_email1: '',
            tutor_application_reference_relationship1: '',
            tutor_application_reference_fname2: '',
            tutor_application_reference_lname2: '',
            tutor_application_reference_email2: '',
            tutor_application_reference_relationship2: '',
            loading: false,
            async submit() {
                $.validator.addMethod("notEqual", function(value, element, params) {
                    if ($(params).val() == value) return false
                    else return true
                }, "Please enter a Unique Value.")
                $('#tutor_character_refer_form').validate({
                    rules: {
                        tutor_application_reference_fname1: 'required',
                        tutor_application_reference_lname1: 'required',
                        tutor_application_reference_fname2: 'required',
                        tutor_application_reference_lname2: 'required',
                        tutor_application_reference_email1: {
                            required: true,
                            email: true,
                        },
                        tutor_application_reference_relationship1: 'required',
                        tutor_application_reference_email2: {
                            required: true,
                            email: true,
                            notEqual: '#tutor_application_reference_email1'
                        },
                        tutor_application_reference_relationship2: 'required'
                    }
                });
                if ($('#tutor_character_refer_form').valid()) {
                this.loading = true;
                let result = await @this.call('updateApplicationReference');
                if (!!result) location.href = "/application-success";
                this.loading = false;
                }

            },
        }))
    })
</script>