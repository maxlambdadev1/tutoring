<div class="container mx-0">
    <div class="row mt-3">
        <div class="col-12">
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a href="#action{{$row->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                        <span class="d-md-block">Actions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#offer-volume{{$row->id}}" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                        <span class="d-md-block">Offer volume</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#application-history{{$row->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        <span class="d-md-block">Tutor application history</span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active " id="action{{$row->id}}">
                    <div class="row">
                        <div class="col-7">
                            <div class="row mb-2">
                                <div class="col-12">
                                    <div class="mb-2">
                                        <label for="comment" class="form-label">Comment</label>
                                        <textarea class="form-control" x-ref="comment{{$row->id}}" id="comment" rows="5"></textarea>
                                    </div>
                                    <input type="button" wire:click="addComment({{$row->id}}, $refs.comment{{$row->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="other-action">
                                        <a class="btn btn-secondary waves-effect waves-light btn-sm" href="https://alchemy.team/tutor/{{$row->id}}">Profile</a>
                                        <input type="button" value="Detail" data-bs-toggle="modal" data-bs-target="#tutorDetailModal{{$row->id}}" class="btn btn-primary btn-sm">
                                        <input type="button" value="Make inactive" data-bs-toggle="modal" data-bs-target="#makeTutorInactiveModal{{$row->id}}" class="btn btn-info btn-sm">
                                        <input type="button" value="Block from jobs" wire:click="blockTutorFromJobs({{$row->id}})" class="btn btn-success btn-sm">
                                        <input type="button" value="{{ $row->online_acceptable_status ? 'Cannot accept online students' : 'Can accept online students'}}" 
                                            wire:click="changeOnlineStatus({{$row->id}})" class="btn btn-danger btn-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 history-detail">
                            @forelse ($row->history as $comment)
                            <div class="mb-1">
                                <div>{{ $comment->comment}}</div>
                                <span class="text-muted"><small>{{ $comment->author }} on {{ $comment->date }}</small></span>
                            </div>
                            @empty
                            There are no any comments for this tutor yet.
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="tab-pane history-detail" id="offer-volume{{$row->id}}">
                    @php
                    $volume_offers = $row->jobs_volume_offer;
                    $volume_count = [];
                    foreach($volume_offers as $v) {
                    $job = $v->job;
                    $job_status = '';
                    if ($job->accepted_by == $row->id) $job_status = 1;
                    $rejected = $row->rejected_job;
                    if (!empty($rejected)) {
                    $rejected_jobs = explode(',', $rejected->job_ids);
                    if (!empty($rejected_jobs) && in_array($job->id, $rejected_jobs)) $job_status = 2;
                    }
                    $t = \DateTime::createFromFormat('d/m/Y H:i',$v->date);
                    $t->setTimeZone( new DateTimeZone('Australia/Sydney'));
                    $volume_count[] = [
                    'details' => $job->child->child_name . " - " . $job->subject . " / " . $job->location . " / " . $job->date . $job->time,
                    'status' => $job_status,
                    'date' => "Sent on" . $t->format('d/m/Y H:i')
                    ];
                    }
                    @endphp
                    @forelse ($volume_count as $volume)
                    <div class="mb-2">
                        {{ $volume['details'] }} {{ $volume['status'] == 2 ? '(Rejected)' :  ($volume['status'] == 1 ? '(Accepted)' : '')}} - <small>{{$volume['date']}}</small>
                    </div>
                    @empty
                    There are no any offers for this tutor yet.
                    @endforelse
                </div>
                <div class="tab-pane history-detail" id="application-history{{$row->id}}">
                    <div class="col-6 history-detail">
                        @if (!empty($row->application) && !empty($row->application->history))
                        @foreach ($row->application->history as $comment)
                        <div class="mb-1">
                            <div>{{ $comment->comment}}</div>
                            <span class="text-muted"><small>{{ $comment->author }} on {{ $comment->date }}</small></span>
                        </div>
                        @endforeach
                        @else
                        There are no any comments for this tutor application yet.
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="makeTutorInactiveModal{{$row->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog" x-data="{is_now{{$row->id}} : true }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Make tutor inactive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <span class="form-label fw-bold">Now &nbsp;</span>
                        <div>
                            <input type="checkbox" id="is_now{{$row->id}}" checked name="is_now{{$row->id}}" data-switch="success" x-ref="is_now{{$row->id}}" x-on:change="is_now{{$row->id}} = !is_now{{$row->id}}">
                            <label for="is_now{{$row->id}}" data-on-label="Yes" data-off-label="No"></label>
                        </div>
                    </div>
                    <div x-show="is_now{{$row->id}} == false" class="mb-3">
                        <label for="schedule_date{{$row->id}}" class="form-label">Schedule Date</label>
                        <input type="text" class="form-control " name="schedule_date{{$row->id}}" id="schedule_date{{$row->id}}" x-ref="schedule_date{{$row->id}}">
                    </div>
                </div>
                <div class="modal-footer" x-data="{ init() { 
                    $('#schedule_date{{$row->id}}').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            todayHighlight: true,
                        });
                    } }">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" 
                        wire:click="makeTutorInactive({{$row->id}}, is_now{{$row->id}}, $refs.schedule_date{{$row->id}}.value)"
                        data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <livewire:admin.components.edit-tutor-modal tutor_id="{{$row->id}}" :key="$row->id" />
</div>