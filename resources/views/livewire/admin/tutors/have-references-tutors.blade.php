<div>
    @php
    $title = "Tutors who have references";
    $breadcrumbs = ["Tutors", "having references"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.tutors.have-references-tutor-table />
                </div>
            </div>
        </div>
    </div>

</div>
