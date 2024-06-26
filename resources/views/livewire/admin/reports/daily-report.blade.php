<div>
    @php
    $title = "Daily Report";
    $breadcrumbs = ["Reports", "Daily"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.reports.daily-report-table />
                </div>
            </div>
        </div>
    </div>
</div>