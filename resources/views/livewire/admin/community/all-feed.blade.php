<div>
    @php
    $title = "All Feed";
    $breadcrumbs = ["Community", "Feed"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    @json($posts)
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
</script>