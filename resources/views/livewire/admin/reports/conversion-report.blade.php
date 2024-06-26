<div>
    @php
    $title = "Conversion Report";
    $breadcrumbs = ["Reports", "Conversion"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.reports.conversion-report-table />
                </div>
            </div>
        </div>
    </div>
</div>