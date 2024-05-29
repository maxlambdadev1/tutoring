<div>
    @php
    $title = "Waiting list";
    $breadcrumbs = ["Leads", "Waiting list"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.leads.all-leads-table job_type="waiting" />
                </div>
            </div>
        </div>
    </div>
</div>
