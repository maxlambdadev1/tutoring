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
                    @if ($focus == 'tutor')
                    <livewire:admin.components.tutor-search-detail tutor_id="{{$id}}" />
                    @elseif ($focus == 'parent')
                    <livewire:admin.components.parent-search-detail parent_id="{{$id}}" />
                    @elseif ($focus == 'child')
                    <livewire:admin.components.child-search-detail child_id="{{$id}}" />
                    @else
                    <p class="p-3 text-center">There are no results.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

