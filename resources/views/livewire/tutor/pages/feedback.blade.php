<div x-data="feedback_init">
    @section('title')
    Feedback // Alchemy Tuition
    @endsection
    @section('description')
    @endsection

    <div class="text-center" style="height:100vh;">
        <div class="container">
            <div class="row">
                <div x-show="!result" class="col-12 mt-4 mt-md-5">
                    <h2 class="text-center mb-4">Your feedback is so valuable to us</h2>
                    <p class="text-center">Please take a moment to let us know how your tutor is doing.</p>
                    <p class="text-center mb-4">We want to ensure every session is inspiring, challenging and above all, valuable for you and your child.</p>
                    <form action="#" id="feedback_form">
                        <div class="mb-3">
                            <label for="rating" class="form-label fw-bold text-center mb-3">Overall, how has your experience with your tutor been so far?</label>
                            <div class="text-center">
                                <div class="rateit rateit-mdi" data-rateit-mode="font" id="rating" x-on:click="getRating" x-on:touchend="getRating" style="font-size:36px;">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comment" x-show="rating == 5" class="form-label fw-bold text-center">Great! What do you love about your tutor?</label>
                            <label for="comment" x-show="rating < 5" class="form-label fw-bold text-center">What can be done better?</label>
                            <textarea class="form-control" wire:model="comment" id="comment" name="comment" rows="5" x-model="comment"></textarea>
                        </div>
                        <div class="mt-4 mb-4 text-center">
                        <button type="button" class="btn btn-warning text-white" :disabled="!comment || rating == 0" x-on:click="insertRegularReview" x-show="!loading">Submit</button>
                        <button type="button" class="btn btn-warning text-white" x-show="loading" disabled>
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Submitting...</button>
                        </div>
                    </form>
                </div>
                <div x-show="!!result && rating == 5" class="col-12 mt-4 mt-md-5 text-center">
                    <h2>Thank you so much for your feedback!</h2>
                    <p class="mb-0"><b>Could you take 30 seconds to give us a positive review on Google?</b></p>
                    <p>Yur 5-star review would really make our day!</p>
                    <a title="Review Alchemy Tuition" target="_self" class="btn btn-primary btn-custom mt-4" href="https://podium.co/VqWjA4G">REVIEW ALCHEMY HERE</a>
                </div>
                <div x-show="!!result && rating < 5" class="col-12 mt-4 mt-md-5 text-center">
                    <h2>Thank you for your feedback!</h2>
                    <p class="mb-4"><b>It will shortly be reviewed by a member of our team and will be in touch if required.</b></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('feedback_init', () => ({
            rating: 0,
            comment: '',
            loading: false,
            result: false,
            async insertRegularReview() {
                $('#feedback_form').validate({
                    rules: {
                        comment: 'required',
                    }
                });
                if ($('#feedback_form').valid()) {
                this.loading = true;
                let result = await @this.call('insertRegularReview', this.rating);
                if (result) this.result = result;
                else setTimeout(() => {
                    location.reload();
                }, 2000);
                this.loading = false;
                }
            },
            getRating() {
                this.rating = $('#rating').rateit('value');
            }
        }))
    })
</script>