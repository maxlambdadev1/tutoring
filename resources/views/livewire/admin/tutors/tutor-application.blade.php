<div>
    @php
    $title = "Tutor application";
    $breadcrumbs = ["Tutors", "Application"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-0">
                        <label for="application_status" class="form-label">Status</label>
                        <select wire:model.live="application_status" id="application_status" class="form-control form-control-sm">
                            <option value="1" selected="selected">Applied as tutor</option>
                            <option value="7">Auto-reject</option>
                            <option value="2">Scheduling interview</option>
                            <option value="3">Interview scheduled</option>
                            <option value="4">Awaiting to register</option>
                            <option value="5">Registered</option>
                            <option value="6">Rejected</option>
                            <option value="9">Closed</option>
                            <option value="">All</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.tutors.tutor-application-table :status="$application_status" :key="$application_status"/>
                </div>
            </div>
        </div>
    </div>

</div>