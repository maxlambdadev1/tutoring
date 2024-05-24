<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Lead Id</div>
                <div class="col-md-6">{{$row->id}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Automation status</div>
                <div class="col-md-6">{!! $row->automation ? '<span class="badge bg-success rounded-pill">Runing</span>' : '<span class="badge bg-danger rounded-pill">Not runing</span>' !!}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Postcode</div>
                <div class="col-md-6">{{$row->parent->parent_postcode}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Source</div>
                <div class="col-md-6">{{$row->source ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Prefered gender</div>
                <div class="col-md-6">{{$row->preferred_gender ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Previously associated tutors</div>
                <div class="col-md-6">{{'-'}}</div>
            </div>
            @php
            $views = 0;
            $visited_tutors_array = [];
            if (!empty($row->visited_tutors)) {
            foreach ($row->visited_tutors as $visited_tutor) {
            $views += $visited_tutor->cnt;
            array_push($visited_tutors_array, $visited_tutor->tutor->tutor_name . "(" . $visited_tutor->cnt . ")");
            }
            }
            $visited_tutors = implode(', ', $visited_tutors_array);
            @endphp
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Views</div>
                <div class="col-md-6">{{$views}} times</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Viewed by</div>
                <div class="col-md-6">{{$visited_tutors}}</div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">What is the main result you are looking for?</div>
                <div class="col-md-6">{{$row->main_result ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">How would you describe their current performance at school?</div>
                <div class="col-md-6">{{$row->performance ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">How would you describe their attitude towards school?</div>
                <div class="col-md-6">{{$row->attitude ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">How would you describe their mind?</div>
                <div class="col-md-6">{{$row->mind ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">How would you describe their personality?</div>
                <div class="col-md-6">{{$row->personality ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">What are their 3 favourite things to do?</div>
                <div class="col-md-6">{{$row->favourite ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Notes</div>
                <div class="col-md-6">{!! $row->job_notes ? nl2br($row->job_notes) : '-' !!}</div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-10">
            <div class="job-items">
                <p class="mb-1"><b>Availabilities:</b></p>
                <div class="availabilities_wrapper">
                    <div class="row">
                        @foreach ($options['total_availabilities'] as $avail)
                        <div class="col-md text-center" data-day="{{$avail->name}}">
                            <h5>{{$avail->name}}</h5>
                            @forelse ($avail->getAvailabilitiesName() as $ele)
                            @if (!empty($row->availabilities))
                            <div class="avail_hours @if(in_array($avail->short_name . '-' . $ele, $row->availabilities)) {{'active'}} @endif">{{$ele}}</div>
                            @else
                            <div class="avail_hours">{{$ele}}</div>
                            @endif
                            @empty
                            <div>Not exist</div>
                            @endforelse
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a href="#action{{$row->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                        <span class="d-md-block">Actions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#reschedule{{$row->id}}" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                        <span class="d-md-block">Reschedule suggestions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#rejection{{$row->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        <span class="d-md-block">Rejection comments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#automation{{$row->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        <span class="d-md-block">Automation comments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#matching-tutors{{$row->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        <span class="d-md-block">Matching tutors</span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="action{{$row->id}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-12">
                                    <div class="mb-2">
                                        <label for="comment" class="form-label">Comment</label>
                                        <textarea class="form-control" x-ref="comment{{$row->id}}" id="comment" rows="3"></textarea>
                                    </div>
                                    <input type="button" wire:click="addComment({{$row->id}}, $refs.comment{{$row->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <div>
                                        <select class="form-select form-select-sm" id="select-status" x-ref="progress_status{{$row->id}}">
                                            @foreach ($options['progress_list'] as $item)
                                            <option value="{{$item}}" {{ $row->progress_status == $item ? 'selected' : ''}}>{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="other-action">
                                        <input type="button" value="Update status" wire:click="updateProgressStatus({{$row->id}}, $refs.progress_status{{$row->id}}.value)" class="btn btn-info btn-sm">
                                        <input type="button" value="Match lead" x-on:click="function() {
                                                Swal.fire({
                                                    icon: 'info',
                                                    title: 'Run automation',
                                                    text: 'Run tutor finding automation?',
                                                    showCancelButton: true,
                                                    showLoaderOnConfirm: true
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        @this.call('matchLead', {{$row->id}});
                                                    }
                                                })}" class="btn btn-success btn-sm">
                                        <input type="button" value="Match hot lead" class="btn btn-secondary btn-sm">
                                        <input type="button" value="Send online tutoring email" x-on:click="function() { 
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'Are you sure?',
                                                    text: 'The online tutoring email will be sent!',
                                                    showCancelButton: true,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        @this.call('sendOnlineEmail', {{$row->id}});
                                                    }
                                                })}" class="btn btn-warning btn-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="other-action">
                                        <input type="button" value="Send welcome email and SMS" x-on:click="function() { @this.call('sendWelcomeEmail', {{$row->id}}); }" class="btn btn-primary btn-sm">
                                        <input type="button" value="Send to waiting list" x-on:click="function() {
                                                Swal.fire({
                                                    type: 'warning',
                                                    title: 'Send to waiting list',
                                                    text: 'Would you like to send this lead to waiting list?',
                                                    showCancelButton: true,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        @this.call('sendToWaitingList1', {{$row->id}});
                                                    }
                                            })}" class="btn btn-warning btn-sm">
                                        <input type="button" value="Assign lead" data-bs-toggle="modal" data-bs-target="#assignLeadModal{{$row->id}}" class="btn btn-info btn-sm">
                                        <input type="button" value="Edit lead" class="btn btn-success btn-sm">
                                        <input type="button" value="Hide lead" class="btn btn-secondary btn-sm">
                                        <input type="button" value="Delete lead" class="btn btn-danger btn-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @forelse ($row->comments as $comment)
                            <div class="mb-1">
                                <div>{{ $comment->comment}}</div>
                                <p class="text-muted"><small>{{ $comment->author }} on {{ $comment->date }}</small></p>
                            </div>
                            @empty
                            There are no any comments for this lead yet.
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="reschedule{{$row->id}}">
                    There are no any comments for this lead yet.
                </div>
                <div class="tab-pane" id="rejection{{$row->id}}">
                    There are no any comments for this lead yet.
                </div>
                <div class="tab-pane" id="automation{{$row->id}}">
                    There are no any comments for this lead yet.
                </div>
                <div class="tab-pane" id="matching-tutors{{$row->id}}">
                    There are no any tutors for this lead yet.
                </div>
            </div>
        </div>
    </div>
    <div id="assignLeadModal{{$row->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Assign lead to</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_tutor{{$row->id}}" class="form-label">Tutors</label>
                        <select name="assigned_tutor" x-ref="assigned_tutor{{$row->id}}" id="assigned_tutor{{$row->id}}" class="form-select">
                            <option value="">Please select...</option>
                            @forelse ($options['all_tutors'] as $tutor)
                            <option value="{{$tutor->id}}">{{ $tutor->user->email }}</option>
                            @empty
                            There are no tutors.
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="availability{{$row->id}}" class="form-label">Select a date and time for the first session based upon Student preference</label>
                        <select name="availability" x-ref="availability{{$row->id}}" id="availability{{$row->id}}" class="form-select">
                            <option value="">Please select...</option>
                            @forelse ($row->availabilities as $item)
                            @php
                            $day = explode('-', $item)[0]; //mon
                            @endphp
                            <option value="{{$item}}">{{ str_replace($day, $options['week_days'][$day], str_replace('-', ' ',$item))  }}</option>
                            @empty
                            There are no availabilities.
                            @endif
                        </select>
                    </div>
                    <p class="text-center">OR</p>
                    <div class="mb-3">
                        <label for="lead-custom-datetime" class="form-label">Select a custom date and time</label>
                        <input type="text" x-ref="lead_custom_datetime{{$row->id}}" class="form-control lead-custom-datetime"  
                            name="lead-custom-datetime{{$row->id}}" id="lead-custom-datetime" value="">
                    </div>
                </div>
                <div class="modal-footer" x-data="{ init() { 
                            $('.lead-custom-datetime').datetimepicker({
                                locale: 'en-au',
                                format: 'DD/MM/YYYY h:mm A',
                                sideBySide: true,
                                minDate: new Date()
                            });
                            } }">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" 
                        wire:click="assignLead({{$row->id}}, {
                            assigned_tutor : $refs.assigned_tutor{{$row->id}}.value,
                            availability : $refs.availability{{$row->id}}.value,
                            custom_date : $refs.lead_custom_datetime{{$row->id}}.value
                        })"
                        data-bs-dismiss="modal">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>