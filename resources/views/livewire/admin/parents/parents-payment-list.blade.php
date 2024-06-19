<div>
    @php
    $title = "List of parents without Stripe ID";
    $breadcrumbs = ["Parents", "Payment details"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.parents.parents-payment-list-table />
                </div>
            </div>
        </div>
    </div>

</div>

