<div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Availability</div>
        <div class="col-md-9">
            <div class="availabilities_wrapper">
                <div class="row">
                    @foreach ($total_availabilities as $item)
                    <div class="col-md text-center" data-day="{{$item->name}}">
                        <h5>{{$item->name}}</h5>
                        @forelse ($item->getAvailabilitiesName() as $ele)
                        @php $avail_hour = $item->short_name . '-' . $ele; @endphp
                        <div class="avail_hours @if(in_array($avail_hour, $availabilities)) active @endif">{{$ele}}</div>
                        @empty
                        <div>Not exist</div>
                        @endforelse
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Subjects</div>
        <div class="col-md-9">
            {{$tutor->expert_sub}}
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 fw-bold">Profile last update</div>
        <div class="col-md-9">
            {{$tutor->last_updated}}
        </div>
    </div>
</div>