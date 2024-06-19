<div>
    @php
    $title = "Current Parents";
    $breadcrumbs = ["Parents", "Current parents"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.parents.current-parents-table />
                </div>
            </div>
        </div>
    </div>

</div>

