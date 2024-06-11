<div>
    @php
    $title = "Tutors";
    $breadcrumbs = ["Tutors", "Current tutors"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.tutors.current-tutors-table  />
                </div>
            </div>
        </div>
    </div>

</div>