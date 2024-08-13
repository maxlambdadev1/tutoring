<div x-data="reference_init">
    @section('title')
    Reference // Alchemy Tuition
    @endsection
    @section('description')
    @endsection

    <div class="" style="height:100vh;">
        <div class="container">
            <div class="row">
                @if ($reason == 'yes')
                <div x-show="!result" class="col-12 mt-4 mt-md-5">
                    <form action="#" id="reference_form" class="mt-3 text-center">
                        <div class="mb-3">
                            <label for="comment" class="form-label fw-bold fs-3">Why you do not recommend this candidate?</label>
                            <textarea class="form-control" wire:model="comment" id="comment" name="comment" rows="5" x-model="comment"></textarea>
                        </div>
                        <div class="mt-4 mb-4 text-center">
                            <button type="button" :disabled="comment.length == 0" class="btn btn-warning text-white col-12 col-md-6" x-on:click="insertTutorReference" x-show="!loading">Submit</button>
                            <button type="button" class="btn btn-warning text-white" x-show="loading" disabled>
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Submitting...</button>
                        </div>
                    </form>
                </div>
                @endif
                <div x-show="!!result || {{$reason == 'no' ? 'true' : 'false'}}" class="col-12 mt-4 mt-md-5 text-center">
                    <h2>Thank you - your support for this application has been recorded!</h2>
                    <p><a href="{{env('MAIN_SITE')}}">Click here</a> to learn more about Alchemy Tuition</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reference_init', () => ({
            comment: '',
            loading: false,
            result: false,
            async insertTutorReference() {
                $('#reference_form').validate({
                    rules: {
                        comment: 'required',
                    }
                });
                if ($('#reference_form').valid()) {
                    this.loading = true;
                    let result = await @this.call('insertTutorReference');
                    if (result) this.result = result;
                    this.loading = false;
                }
            },
        }))
    })
</script>