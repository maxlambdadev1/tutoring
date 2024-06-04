<div>
    @php
    $title = "Sessions";
    $breadcrumbs = ["Sessions", "All sessions"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.sessions.all-sessions-table />
                </div>
            </div>
        </div>
    </div>

</div>
