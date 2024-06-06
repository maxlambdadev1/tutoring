<div>
    @php
    $title = "No sessions in 2 weeks";
    $breadcrumbs = ["Sessions", "No-session-2-weeks"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.sessions.no-session-2-weeks-table />
                </div>
            </div>
        </div>
    </div>
</div>
