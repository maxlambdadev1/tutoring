<div>
    @php
    $title = "Add session";
    $breadcrumbs = ["Sessions", "Add"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-session-alert />
                </div>
            </div>
        </div>
    </div>
</div>

