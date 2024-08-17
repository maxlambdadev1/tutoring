<div x-data="payment_cc_init">
    @section('title')
    Payment details
    @endsection
    @section('description')
    @endsection

    <div class="" style="height:100vh;">
        <div class="container">
            <div class="row mt-4 mt-md-5">
                <div class="col-12 mb-4">
                    <h2 class="text-center mb-4">Submit your payment details.</h2>
                    <p class="text-center">We endeavour to make our payment process as easy and convenient as possible for you by utilising an automated payment system that securely stores your payment details and charges you 24 hours after each session occurs.</p>
                    <p class="text-center">This is not a direct debit - you are not signing up to regular payments every week. We simply hold your details on file and process your payment after each session occurs.</p>
                </div>
                <div class="col-md-3 mb-3 text-center">
                    <h3 class="text-warning">1</h3>
                    <p class="fw-bold">Enter your payment details once.</p>
                    <p>These are stored directly with STRIPE, an international payment processing company that uses the latest in security and encryption to ensure the safety of your information.</p>
                </div>
                <div class="col-md-3 mb-3 text-center">
                    <h3 class="text-warning">2</h3>
                    <p class="fw-bold">Notification of scheduled payment.</p>
                    <p>Following a session with your tutor you will receive an email containing feedback from the session, the length of the session and the amount to be charged.</p>
                </div>
                <div class="col-md-3 mb-3 text-center">
                    <h3 class="text-warning">3</h3>
                    <p class="fw-bold">Wait 24 hours to be charged.</p>
                    <p>In 24 hours your debit or credit card will be charged for the session, giving you enough time to let us know if there are any issues.</p>
                </div>
                <div class="col-md-3 mb-3 text-center">
                    <h3 class="text-warning">4</h3>
                    <p class="fw-bold">Receive receipt by email.</p>
                    <p>As soon as payment has been processed you will receive receipt of payment by email. We can send you a full account statement at any time by request.</p>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4 text-center">
                    <div x-show="!result">
                    <h2>Please submit your debit or credit card details below.</h2>
                    <p class="text-muted">We accept Visa, Mastercard and Visa/Mastercard debit.</p>
                    <form action="#" id="payment_cc_form">
                        <div class="mb-2">
                            <input type="text" class="form-control " name="card_name" id="card_name" wire:model="card_name" x-model="card_name" placeholder="Name on card">
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control " name="card_number" id="card_number" wire:model="card_number" x-model="card_number" placeholder="Card number">
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control " name="expiry" id="expiry" wire:model="expiry" x-model="expiry" placeholder="Expiry date(MM/YY)">
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control " name="cvc" id="cvc" wire:model="cvc" x-model="cvc" placeholder="CVC">
                        </div>
                        <div class="mt-3 text-center">
                            <button type="button" class="btn btn-warning text-white col-md-12 mb-2" x-on:click="submit" x-show="!loading">Confirm my next session</button>
                            <button type="button" class="btn btn-warning text-white col-md-12 mb-2" x-show="loading" disabled>
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Submitting...</button>
                        </div>
                        <p class="text-muted text-center">Upon submission of payment details, any outstanding lessons that have occured will be charged - with all future sessions being charged 24 hours after they occur. </p>
                    </form>
                    </div>
                    <div x-show="!!result" class="mt-4 mt-md-5 text-center bg-warning text-white p-3">
                        <h2 class="mb-3">THANK YOU</h2>
                        <p>Your details have been submitted.</p>
                        <p>You will be notified by email prior to each payment.</p>
                        <p>If you have any questions please call our office on 02 8294 8215</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('payment_cc_init', () => ({
            card_name: '',
            card_number: '',
            expiry: '',
            cvc: '',
            loading: false,
            result: false,
            async submit() {
                $('#payment_cc_form').validate({
                    rules: {
                        card_name: 'required',
                        card_number: 'required',
                        expiry: 'required',
                        cvc: 'required',
                    }
                });
                if ($('#payment_cc_form').valid()) {
                    this.loading = true;
                    let result = await @this.call('saveParentCc');
                    if (result) this.result = result;
                    this.loading = false;
                }
            },
        }))
    })
</script>