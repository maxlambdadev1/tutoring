<div x-data="tutor_vote_init">
    @section('title')
    Nominate your tutor for Tutor Of The Month // Alchemy Tuition
    @endsection
    @section('description')
    @endsection

    <div class="" style="height:100vh;">
        <div class="container">
            <div class="row">
                <div x-show="!result" class="col-12 mt-4 mt-md-5">
                    <div class="text-center">
                        <img src="/images/totm.png" width="300" />
                    </div>
                    <p class="text-center">Has your tutor done an outstanding job with your child?</p>
                    <p class="text-center">Do they go above and beyond to make sure your child is well supported?</p>
                    <p class="text-center">Nominate them to be our next tutor of the month and you both get rewarded.</p>
                    <p class="text-center">If we select your vote for tutor of the month your tutor receives a great prize and a letter of recommendation. You will also receive a $25 Prezzee gift card as a way of saying thank you (can be used at many top retailers, like Myer, Target, Kmart, Coles and Woolworths).</p>
                    <p class="text-center">Cast your vote below. Winning tutors will be announced at the start of each month and winners will be contacted by email. Your vote is valid for 3 months, so even if your tutor isn't selected this month they may be in coming months</p>
                    <form action="#" id="tutor_vote" class="mt-3">
                        <div class="mb-3">
                            <label for="state" class="form-label fw-bold">Please select your tutor.</label>
                            <select name="tutor_id" id="tutor_id" class="form-select" wire:model="tutor_id" x-model="tutor_id">
                                <option value=""></option>
                                @forelse ($tutors as $tutor)
                                <option value="{{$tutor->id}}">{{$tutor->tutor_name}}({{$tutor->tutor_email}})</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label fw-bold">What do you love about your tutor?</label>
                            <textarea class="form-control" wire:model="comment" id="comment" name="comment" rows="5" x-model="comment"></textarea>
                        </div>
                        <div class="mt-4 mb-4 text-center">
                            <button type="button" :disabled="!tutor_id || comment.length == 0" class="btn btn-warning text-white col-12 col-md-6" x-on:click="insertRegularReview" x-show="!loading">Submit</button>
                            <button type="button" class="btn btn-warning text-white" x-show="loading" disabled>
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Submitting...</button>
                        </div>
                    </form>
                </div>
                <div x-show="!!result" class="col-12 mt-4 mt-md-5 text-center">
                    <h2>Thank you so much for your feedback!</h2>
                    <p>Could you take 30 seconds to give us a positive review on Google? Your 5-star review would really make our day!</p>
                    <a title="Review Alchemy Tuition" target="_self" class="btn btn-primary btn-custom mt-4" href="https://podium.co/GdZN8aE">REVIEW ALCHEMY HERE</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tutor_vote_init', () => ({
            tutor_id: 0,
            comment: '',
            loading: false,
            result: false,
            async insertRegularReview() {
                $('#tutor_vote').validate({
                    rules: {
                        tutor_id: 'required',
                        comment: 'required',
                    }
                });
                if ($('#tutor_vote').valid()) {
                    this.loading = true;
                    let result = await @this.call('insertRegularReview');
                    if (result) this.result = result;
                    this.loading = false;
                }
            },
        }))
    })
</script>