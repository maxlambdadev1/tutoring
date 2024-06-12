<div>
    @php
    $title = "New Tutors";
    $breadcrumbs = ["Tutors", "New tutors"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.tutors.new-tutors-table  />
                </div>
            </div>
        </div>
    </div>

</div>