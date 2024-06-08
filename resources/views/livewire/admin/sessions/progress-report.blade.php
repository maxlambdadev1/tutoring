<div>
    @php
    $title = "Progress report";
    $breadcrumbs = ["Sessions", "Progress report"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.sessions.progress-report-table />
                </div>
            </div>
        </div>
    </div>
</div>
