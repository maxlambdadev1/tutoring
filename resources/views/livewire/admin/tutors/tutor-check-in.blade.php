<div>
    @php
    $title = "Tutor Check In";
    $breadcrumbs = ["Tutors", "Check in"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-0">
                        <label for="filter" class="form-label">Status</label>
                        <select wire:model.live="filter" id="filter" class="form-control form-control-sm">
                            <option value="active" selected="selected">Active</option>
                            <option value="">All</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.tutors.tutor-check-in-table :filter="$filter" :key="$filter"/>
                </div>
            </div>
        </div>
    </div>

</div>