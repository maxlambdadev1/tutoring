<div>
    @php
    $title = "New leads";
    $breadcrumbs = ["Leads", "New leads"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.leads.all-leads-table job_type="new" />
                </div>
            </div>
        </div>
    </div>
</div>
