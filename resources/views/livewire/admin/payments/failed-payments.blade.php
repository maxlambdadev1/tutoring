<div>
    @php
    $title = "Failed payments";
    $breadcrumbs = ["Payments", "Failed"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.payments.failed-payments-table />
                </div>
            </div>
        </div>
    </div>
</div>