<div>
    @php
    $title = "Tutor First Session";
    $breadcrumbs = ["Tutors", "First session"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.tutors.tutor-check-in-table />
                </div>
            </div>
        </div>
    </div>

</div>