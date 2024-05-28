<div>
    @php
    $title = "Leads screening";
    $breadcrumbs = ["Leads", "Leads screening"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.leads.all-leads-table job_type="screening" />
                </div>
            </div>
        </div>
    </div>
</div>
