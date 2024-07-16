<div>
    @php
    $title = "Update Availabilities";
    $breadcrumbs = ["Your detail", "Availabilities"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn{{ $is_selected_all ? '' : '-outline'}}-warning waves-effect waves-light w-100" wire:click="selectAllAvailabilities(true)">I am completely available</button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn{{ $is_selected_all ? '-outline' : ''}}-secondary waves-effect waves-light w-100"wire:click="selectAllAvailabilities(false)">I am completely unavailable</button>
                        </div>
                    </div>
                    <div class="availabilities_wrapper">
                        <div class="row mb-3">
                            @foreach ($total_availabilities as $item)
                            <div class="col-md text-center" data-day="{{$item->name}}">
                                <h5>{{$item->name}}</h5>
                                @forelse ($item->getAvailabilitiesName() as $ele)
                                @php $avail_hour = $item->short_name . '-' . $ele; @endphp
                                <div class="avail_hours @if(in_array($avail_hour, $availabilities)) active @endif" wire:click="toggleAvailItemStatus('{{$avail_hour}}')">{{$ele}}</div>
                                @empty
                                <div>Not exist</div>
                                @endforelse
                            </div>
                            @endforeach
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm" wire:click="updateAvailabilities">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>