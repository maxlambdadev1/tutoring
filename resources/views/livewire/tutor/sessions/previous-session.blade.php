<div>
    @php
    $title = "Previous sessions";
    $breadcrumbs = ["Sessions", "Previous sessions"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:tutor.sessions.previous-sessions-table />
                </div>
            </div>
        </div>
    </div>
</div>
