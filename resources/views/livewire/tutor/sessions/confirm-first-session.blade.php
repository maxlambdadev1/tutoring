<div x-data="confirm_session_init">
    @php
    $title = "Confirm sessions";
    $breadcrumbs = ["Sessions", "Confirm"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    2
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var confirm_session_init = () => {
        Alpine.data('confirm_session_init', () => ({
            loading: false,

            init() {
            },
            async submit() {
                this.loading = true;
                let result = await @this.call('reactivateAndScheduleLesson', this.session_date, this.session_time)
                if (result) this.result = result;
                this.loading = false;
            },
        }))
    }
    
    if (typeof Alpine == 'undefined') {
        document.addEventListener('alpine:init', () => { confirm_session_init(); });
    } else {
        confirm_session_init();
    }
</script>