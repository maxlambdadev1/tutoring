<div>
    @php
    $title = "Special requirement approval";
    $breadcrumbs = ["Leads", "Special requirement Approval"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.leads.all-leads-table job_type="special-requirement" />
                </div>
            </div>
        </div>
    </div>
</div>
