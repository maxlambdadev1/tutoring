<div>
    @php
    $title = "Focus leads";
    $breadcrumbs = ["Leads", "Focus leads"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.leads.all-leads-table job_type="focus" />
                </div>
            </div>
        </div>
    </div>
</div>
