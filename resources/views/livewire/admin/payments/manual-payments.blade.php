<div>
    @php
    $title = "Manual payment Lessons";
    $breadcrumbs = ["Payments", "Manual"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.payments.manual-payments-table />
                </div>
            </div>
        </div>
    </div>
</div>