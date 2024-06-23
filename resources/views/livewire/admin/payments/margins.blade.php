<div>
    @php
    $title = "Margins";
    $breadcrumbs = ["Payments", "Margins"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.payments.margins-table />
                </div>
            </div>
        </div>
    </div>
</div>