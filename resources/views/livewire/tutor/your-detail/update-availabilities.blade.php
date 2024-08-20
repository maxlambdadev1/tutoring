<div>
    @php
    $title = "Update Availabilities";
    $breadcrumbs = ["Your detail", "Availabilities"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row" x-data="update_availabilities_init">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-warning waves-effect waves-light w-100" x-on:click="selectAllAvailabilities(true)">I am completely available</button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-secondary waves-effect waves-light w-100" x-on:click="selectAllAvailabilities(false)">I am completely unavailable</button>
                        </div>
                    </div>
                    <div class="availabilities_wrapper mb-4 px-3">
                        <div class="row">
                            @php $i = 0; $total_av = []; @endphp
                            @foreach ($total_availabilities as $item)
                            <div class="col-md text-center" data-day="{{$item->name}}">
                                <h5>{{$item->name}}</h5>
                                @forelse ($item->getAvailabilitiesName() as $ele)
                                @php
                                $avail_hour = $item->short_name . '-' . $ele;
                                $total_av[] = $avail_hour;
                                $i++;
                                @endphp
                                <div class="avail_hours p-0 w-100">
                                    <input type="checkbox" class="d-none" id="availability-{{$i}}" value="{{$avail_hour}}" x-model="availabilities" />
                                    <label for="availability-{{$i}}" class="text-center py-1 w-100">{{$ele}}</label>
                                </div>
                                @empty
                                <div>Not exist</div>
                                @endforelse
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm" x-on:click="updateAvailabilities">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var total_availabilities = @json($total_av);
    var availabilities = @json($availabilities);

    var update_availabilities_init = () => {
        Alpine.data('update_availabilities_init', () => ({
            availabilities: availabilities,
            selectAllAvailabilities(flag) {
                if (!!flag) this.availabilities = total_availabilities;
                else this.availabilities = [];
            },
            updateAvailabilities() {
                @this.call('updateAvailabilities', this.availabilities);
            }
        }))
    }
    
    if (typeof Alpine == 'undefined') {
        document.addEventListener('alpine:init', () => { update_availabilities_init(); });
    } else {
        update_availabilities_init();
    }
</script>