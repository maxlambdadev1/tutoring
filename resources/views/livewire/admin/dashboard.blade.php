<div>
    @php
    $title = "Home";
    $breadcrumbs = ["Dashboard"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row" x-data="dashboard_init">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>

<script>    
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboard_init', () => ({
        }))
    })
</script>