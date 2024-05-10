<x-admin-app-layout>
    @php
        $title = "Home";
        $breadcrumbs = ["Dashboard"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    Home page
                </div>
            </div>
        </div>
    </div>
    
</x-admin-app-layout>
