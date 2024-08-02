<div>
    <div id="rescheduleFirstSessionModal{{$job->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Propose a different first session date and time</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="accordion mb-3" id="accordionExample{{$job->id}}">
                                @php $index = 0; @endphp
                                @foreach ($total_availabilities as $item)
                                <div class="accordion-item">
                                    <h2 class="accordion-header mt-0" id="heading{{$item->name}}">
                                        <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$item->name}}" aria-expanded="false" aria-controls="collapse{{$item->name}}">
                                            {{$item->name}}
                                        </button>
                                    </h2>
                                    <div id="collapse{{$item->name}}" class="accordion-collapse collapse" aria-labelledby="heading{{$item->name}}" data-bs-parent="accordionExample{{$job->id}}">
                                        @foreach ($item->getAvailabilitiesName() as $ele)
                                        @php $avail_hour = $item->short_name . '-' . $ele; $index++; @endphp
                                        <div class="avail_hours w-100 @if(in_array($avail_hour, $job->availabilities)) active @endif {{$index}}" data-value="{{$avail_hour}}" x-on:click="$('#rescheduleFirstSessionModal{{$job->id}} .avail_hours.{{$index}}').toggleClass('active')">
                                            {{$ele}}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check mb-2">
                                <input type="checkbox" id="make_av" name="make_av" class="form-check-input" x-ref="make_av">
                                <label for="make_av" class="form-check-label">Update these times as my availabilities</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="rescheduleJob({{$job->id}}, $('#rescheduleFirstSessionModal{{$job->id}} .avail_hours.active').map(function(index, item) { return $(item).attr('data-value')}).get(), $refs.make_av.checked)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>