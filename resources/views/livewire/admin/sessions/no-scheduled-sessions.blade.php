<div>
    @php
    $title = "No Scheduled Sessions";
    $breadcrumbs = ["Sessions", "No-scheduled"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.sessions.no-scheduled-sessions-table />
                </div>
            </div>
        </div>
    </div>
</div>
