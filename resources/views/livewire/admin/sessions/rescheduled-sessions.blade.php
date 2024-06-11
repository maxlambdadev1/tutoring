<div>
    @php
    $title = "Rescheduled sessions";
    $breadcrumbs = ["Sessions", "Rescheduled"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.sessions.reschedule-session-table  />
                </div>
            </div>
        </div>
    </div>

</div>