<div>
    @php
    $title = "Not continuing sessions";
    $breadcrumbs = ["Sessions", "Not continuing sessions"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.sessions.not-continuing-sessions-table />
                </div>
            </div>
        </div>
    </div>
</div>
