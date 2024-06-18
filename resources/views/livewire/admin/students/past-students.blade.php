<div>
    @php
    $title = "Past Students";
    $breadcrumbs = ["Students", "Past Students"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.students.students-table status="0"/>
                </div>
            </div>
        </div>
    </div>

</div>

