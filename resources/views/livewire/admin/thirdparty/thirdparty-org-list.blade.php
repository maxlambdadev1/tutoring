<div>
    @php
    $title = "Organisation list";
    $breadcrumbs = ["Thirdparty", "Organisations"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <x-session-alert />
                    <livewire:admin.thirdparty.thirdparty-organisations-table />
                </div>
            </div>
        </div>
    </div>
</div>
