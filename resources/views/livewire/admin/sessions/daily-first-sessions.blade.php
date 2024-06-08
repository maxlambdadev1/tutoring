<div>
    @php
    $title = "Daily first sessions";
    $breadcrumbs = ["Sessions", "daily first sessions"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.sessions.daily-first-sessions-table />
                </div>
            </div>
        </div>
    </div>
</div>
