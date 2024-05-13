<div>
    @php
    $title = "Availabilities";
    $breadcrumbs = ["Owner", "Setting", "Availabilities"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-session-alert />
                    <h3>Update availabilities</h3>
                    <hr>
                    <form id="avail_form" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">{{__('Day')}}</label>
                                    <select wire:model="name" class="form-select @error('name') is-invalid @enderror" id="name" name="name" required>
                                        <option value="">Please select...</option>
                                        @foreach ($availabilities as $ele)
                                        <option value="{{$ele->id}}">{{$ele->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                    <div class=" invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="">{{__('Time')}}</label>
                                    <div class="input-group" id="timepicker-input-group">
                                        <input id="timepicker" type="text" class="form-control">
                                        <span class="input-group-text" title="Add availability"><i class="dripicons-plus add-time"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="availabilities">{{__('Availabilities')}}</label>
                                    <div class="border border-light p-1">
                                        <ul class="availabilities_list">
                                            @foreach ($paramAvailabilities as $ele)
                                            <li class="bg-primary mb-1 item">
                                                <button type="button" wire:click="deleteItemFromAvailabilities({{ $ele }})"><i class="mdi mdi-close"></i></button>
                                                <span>{{ $ele }}</span>
                                            </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                    <select wire:model="paramAvailabilities" class="form-select d-none @error('paramAvailabilities') is-invalid @enderror" id="availabilities" multiple name="availabilities[]" required>
                                        @foreach ($paramAvailabilities as $ele)
                                        <option value="{{ $ele }}" selected>{{ $ele }}</option>
                                        @endforeach
                                    </select>
                                    @error('paramAvailabilities')
                                    <div class=" invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary" wire:click="updateAvailabilities">Update</button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Availabilities</h3>
                    </div>
                    <hr>
                    <div class="table-responsive-md">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($availabilities as $item)
                                <tr>
                                    <td>{{ (($availabilities->currentPage() - 1 ) * $availabilities->perPage() ) + $loop->iteration }}</td>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        @foreach ($item->getAvailabilitiesName() as $item)
                                        <li class="bg-primary mb-1 item">
                                            <span>{{$item}}</span>
                                        </li>
                                        @endforeach
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">Not exist any availabilities</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix mt-2">
                        <div class="float-left" style="margin: 0;">
                            <p>Total <strong style="color: red">{{ $availabilities->total() }}</strong></p>
                        </div>
                        <div class="float-right" style="margin: 0;">
                            {{ $availabilities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@section('script')
<script>
    $(document).ready(function() {
        let form_url = $('#avail_form').attr('action')
        let removed_time = '';
        $("#timepicker").timepicker({
            defaultTime: '08:00 AM',
            showSeconds: 0,
            minuteStep: 5,
            icons: {
                up: "mdi mdi-chevron-up",
                down: "mdi mdi-chevron-down"
            },
            appendWidgetTo: "#timepicker-input-group"
        })

        $('.add-time').on('click', function() {
            if ($('#name').val() == '') return
            let selected_time = $('#timepicker').val()
            if (selected_time !== '' && checkValidItem(selected_time, $('#availabilities option'))) {
                $('#timepicker').blur();
                $('#name').focus();
                @this.call('addItemToAvailabilities', selected_time);
            }
        })
        $('body').on('click', '.availabilities_list li.item button', function() {
            removed_time = $(this).data('id')
            $(this).parent().remove()
            removeItem(removed_time, $('#availabilities option'))
        })
        $('#name').on('change', function() {
            @this.call('resetParamAvailabilities');
        })

    })
</script>
@endsection