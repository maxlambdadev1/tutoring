<div>
    @php
    $title = "Unconfirmed sessions";
    $breadcrumbs = ["Sessions", "Unconfirmed"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:tutor.sessions.unconfirmed-sessions-table />
                </div>
            </div>
        </div>
    </div>
</div>
