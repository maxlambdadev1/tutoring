<div>
    @php
    $title = "No Scheduled Sessions";
    $breadcrumbs = ["Sessions", "No-scheduled"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-0">
                        <label for="filter" class="form-label">Filter</label>
                        <select wire:model.live="filter" id="filter" class="form-control form-control-sm">
                            <option value="">All</option>
                            <option value="active">Active</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.sessions.no-scheduled-sessions-table :filter="$filter" :key="$filter"/>
                </div>
            </div>
        </div>
    </div>
</div>
