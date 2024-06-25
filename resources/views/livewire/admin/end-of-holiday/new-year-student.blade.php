<div>
    @php
    $title = "End of holiday student list(Not continuing)";
    $breadcrumbs = ["End of holiday", "Student list(Not continuing)"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.end-of-holiday.new-year-student-table type="not-continuing"/>
                </div>
            </div>
        </div>
    </div>
</div>
