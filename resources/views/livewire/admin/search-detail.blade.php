<div>
    @php
    $title = "Detail";
    $breadcrumbs = ["Search", "Detail"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    {{$focus}} {{$id}}
                </div>
            </div>
        </div>
    </div>

</div>

