<div>
    @php
    $title = "Google Ads Booking Sessions";
    $breadcrumbs = ["Reports", "Google sessions"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.reports.all-sessions-table google_ads="1" />
                </div>
            </div>
        </div>
    </div>
</div>