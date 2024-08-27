<div>
    @section('title')
    Offer Break
    @endsection
    @section('description')
    @endsection

    <div class="" style="height:100vh;" x-data="opt_out_init">
        <div class="container">
            <div class="row mt-4 mt-md-5">
                <div class="col-12 mb-4">
                    <h2 class="text-center mb-4">Take a break from student offers</h2>
                    <p class="text-center text-muted fs-3">If you are at full capacity or currently not looking to take on students, you can opt out of our automated student offers below.</p>
                    <p class="text-center fs-4">Please note - you may still receive manual offers from our team.</p>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="break_day" class="form-label fw-bold">Opt out of student offers for:</label>
                        <select name="break_day" id="break_day" class="form-select" wire:model="break_day" x-model="break_day">
                            <option value=""></option>
                            <option value="2">2 weeks</option>
                            <option value="4">4 weeks</option>
                            <option value="6">6 weeks</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">I think what makes me a great tutor is...</label>
                        <textarea class="form-control" wire:model="reason" id="reason" rows="5" x-model="reason"></textarea>
                    </div>
                    <div class="mt-3 text-center">
                        <button type="button" class="btn btn-warning text-white col-md-12 mb-2" x-on:click="submit" x-show="!loading">Confirm</button>
                        <button type="button" class="btn btn-warning text-white col-md-12 mb-2" x-show="loading" disabled>
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Confirming...</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('opt_out_init', () => ({
            break_day: '',
            reason: '',
            loading: false,
            async submit() {
                if (this.break_day == '' || this.reason == '') {
                    toastr.error("Please input the date and reason");
                    return;
                }
                this.loading = true;
                let break_day = await @this.call('setBreakDay');
                if (!!break_day) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: `Done. You won’t receive any further automated student offers until ${break_day}. If you change your mind just get in touch by livechat and we can fix things up!`,
                        showCancelButton: false,
                    }).then((result) => {
                        window.location = '/';
                    })
                }
                this.loading = false;
            },
        }))
    })
</script>