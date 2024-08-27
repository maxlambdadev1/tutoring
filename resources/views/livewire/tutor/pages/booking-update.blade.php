<div>
    @section('title')
    Your booking with Alchemy Tuition
    @endsection
    @section('description')
    @endsection

    <div class=""  x-data="booking_update_init">
        <div class="container">
            <div class="row mt-4 mt-md-5">
                <div x-show="!result1 && !result2" class="col-12 mb-4">
                    <h1 class="text-center fw-semibold">Tutoring for {{$job->child->child_name ?? ''}}</h1><br><br>
                    <p>Thank you for your patience as we work on matching up the perfect tutor for {{$job->child->first_name ?? ''}}.</p><br>
                    <p>Unfortunately it seems all our tutors near you are at full capacity, and so we wanted to reach out to see if you might consider trialing <strong>online tutoring?</strong></p><br>
                    <p>We understand many parents feel that their kids won't respond well to an online tutor, but in our experience we have found it to offer just as strong outcomes as face to face lessons - especially for students that are already very comfortable working on screens.</p><br>
                    <p style="font-weight: 700; color: #ffa200;">Here's why we think you'll love working with an Alchemy online tutor:</p>
                    <p>🗓️ Complete flexibility - lessons can happen from anywhere in the world, at any time and on any device. Simply join our online classroom from your browser and meet your tutor there.</p>
                    <p>👍 Save money - online tutoring is a more affordable alternative to in-home lessons.</p>
                    <p>🙋 A greater pool of tutors with more specialisations - all Alchemy tutors maintain our high standard of excellence, but when online you aren't restricted to just those in your neighborhood. We can line up the perfect tutor for your child without any geographical limitations.</p>
                    <p>🧑‍💻 Utilise our unique online classroom - built to simulate an in-person lesson as close as possible, our custom built classroom makes lessons engaging and fun. Your child will meet your tutor on video call and have access to a shared whiteboard, quizzes, screen sharing and more.</p><br>
                    <p><strong>We are so confident your child will thrive with an online tutor that we guarantee it.</strong> If they don't completely love the first lesson together then there is no charge. Try it out risk free and see how your child responds!</p><br>
                    <p>Please let us know if you are willing to change your booking to online tutoring below and we will handle it all for you. We can get most online students matched within a day, and we will send you all the details you need by email and SMS as soon as it is confirmed.</p><br>
                    <p><strong>So, what do you think?</strong></p>
                    <div class="mt-3 text-center row">
                        <div class="col-12 col-md-2"></div>
                        <div class="col-12 col-md-4 mb-2">
                            <button type="button" class="btn btn-warning text-white w-100" x-on:click="changeJobToOnline" x-show="!loading1">Yes, I will try online tutoring</button>
                            <button type="button" class="btn btn-warning text-white w-100" x-show="loading1" disabled>
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Submitting...</button>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <button type="button" class="btn btn-dark text-white w-100" x-on:click="noChangeJobToOnline" x-show="!loading2">No, I will wait for an in-home tutor to come up</button>
                            <button type="button" class="btn btn-dark text-white w-100" x-show="loading2" disabled>
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Submitting...</button>
                        </div>
                    </div>
                </div>
                <div x-show="!!result1" class="col-12 text-center">
                    <h1 class="fw-bold mb-5">Thank you!</h1>
                    <p>We know your child is going to thrive with one of our online tutors!</p>
                    <p>We will get it all sorted and confirm with you as soon as it is lined up.</p>
                    <p>If you need anything please don't hesitate to <a href="{{env('MAIN_SITE')}}/#contact-section">get in touch!</a></p>
                </div>
                <div x-show="!!result2" class="col-12 text-center">
                    <h1 class="fw-bold mb-5">Thanks for letting us know.</h1>
                    <p>We understand online tutoring isn't for everyone.</p>
                    <p>We will continue working on an in-person tutor and will be in touch with next steps if we still can't get your child matched up.</p>
                    <p>If you need anything please don't hesitate to <a href="{{env('MAIN_SITE')}}/#contact-section">get in touch!</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('booking_update_init', () => ({
            loading1: false,
            result1: false,
            loading2: false,
            result2: false,
            async changeJobToOnline() {
                this.loading1 = true;
                let result1 = await @this.call('changeJobToOnline');
                if (!!result1) {
                    this.result1 = result1;
                }
                this.loading1 = false;
            },
            async noChangeJobToOnline() {
                this.loading2 = true;
                let result2 = await @this.call('noChangeJobToOnline');
                if (!!result2) {
                    this.result2 = result2;
                }
                this.loading2 = false;
            },
        }))
    })
</script>