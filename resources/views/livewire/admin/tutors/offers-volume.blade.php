<div>
    @php
    $title = "Dormant tutors";
    $breadcrumbs = ["Tutors", "Dormant"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.tutors.offers-volume-table />
                </div>
            </div>
        </div>
    </div>

</div>