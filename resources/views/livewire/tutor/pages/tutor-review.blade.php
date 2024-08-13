<div x-data="tutor_review_init">
    @section('title')
    Alchemy
    @endsection
    @section('description')
    @endsection

    <div class="text-center" style="height:100vh;">
        <div class="container">
            <div class="row">
                <div x-show="!result" class="col-12 mt-4 mt-md-5">
                    <h2 class="text-center mb-4">REVIEW {{$tutor->tutor_name}}</h2>
                    <p class="text-center mb-4">{{$child->child_name}}'s progress report we ask that you take 2 minutes to give your tutor a quick review - this helps boost their profile score and enables them to take on more students in the future.</p>
                    <form action="#" id="progress_report">
                        <div class="mb-3">
                            <label for="rating" class="form-label fw-bold text-center mb-3">Overall, how would you rate your tutor?</label>
                            <div class="text-center">
                                <div class="rateit rateit-mdi" data-rateit-mode="font" id="rating" x-on:click="getRating" x-on:touchend="getRating" style="font-size:36px;">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label fw-bold text-center">Please add a comment to describe your rating:</label>
                            <textarea class="form-control" wire:model="comment" id="comment" name="comment" rows="5" x-model="comment"></textarea>
                        </div>
                        <div class="mt-4 mb-4 text-center">
                            <button type="button" class="btn btn-warning col-12 col-md-6 text-white" x-on:click="showConfirmModal">Submit report</button>
                        </div>
                    </form>
                </div>
                <div x-show="!!result" class="col-12 mt-4 mt-md-5 text-center">
                    <h2>Thank you</h2>
                    <p>Your review has been submitted. Check your email for your progress report.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade in" id="report-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h3 class="bold w-100">Session report details</h3>
                </div>
                <div class="modal-body">
                    <p class="mb-1 fw-bold">Overall, how would you rate your tutor?</p>
                    <p class="mb-3"><span x-text="rating"></span> stars</p>
                    <p class="mb-1 fw-bold">Please add a comment to describe your rating:</p>
                    <p x-text="comment" class="mb-3"></p>
                    <div class="text-center">
                        <button type="button" class="btn btn-warning text-white" x-on:click="submitReport" x-show="!loading">Submit</button>
                        <button type="button" class="btn btn-warning text-white" x-show="loading" disabled>
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Submitting...</button>
                        <button type="button" class="btn btn-secondary" x-on:click="cancel">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tutor_review_init', () => ({
            rating: 0,
            comment: '',
            loading: false,
            result: false,
            showConfirmModal() {
                $('#progress_report').validate({
                    rules: {
                        comment: 'required',
                    }
                });
                if ($('#progress_report').valid()) {
                    $('#report-modal').modal('show');
                }
            },
            cancel() {
                $('#report-modal').modal('hide');
            },
            async submitReport() {
                this.loading = true;
                let result = await @this.call('submitReport', this.rating);
                if (result) this.result = result;
                else setTimeout(() => {
                    location.reload();
                }, 2000);
                this.loading = false;
                $('#report-modal').modal('hide');                
            },
            getRating() {
                this.rating = $('#rating').rateit('value');
            }
        }))
    })
</script>