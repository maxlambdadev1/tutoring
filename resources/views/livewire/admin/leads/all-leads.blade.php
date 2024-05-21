<div>
    @php
    $title = "All leads";
    $breadcrumbs = ["Leads", "All leads"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.leads.all-leads-table />
                </div>
            </div>
        </div>
    </div>
</div>