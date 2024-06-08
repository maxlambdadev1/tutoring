<div class="container mx-0">
    <div class="row">
        <div class="col-5">
            <h3 class="fw-bold">Student details</h3>
            <div class="row">
                <div class="col-5 fw-bold">Student school:</div>
                <div class="col-7">{{ $row->child->child_school ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Student grade:</div>
                <div class="col-7">{{ $row->child->child_year ?? '-' }}</div>
            </div>
            <h3 class="fw-bold">Tutor details</h3>
            <div class="row">
                <div class="col-5 fw-bold">Tutor address:</div>
                <div class="col-7">{{ $row->tutor->address ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Tutor postcode:</div>
                <div class="col-7">{{ $row->tutor->postcode ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Tutor Stripe ID:</div>
                <div class="col-7">{{ $row->tutor->tutor_stripe_user_id ?? '-' }}</div>
            </div>
        </div>
        <div class="col-7">
            <h3 class="fw-bold">Parent details</h3>
            <div class="row">
                <div class="col-5 fw-bold">Parent address:</div>
                <div class="col-7">{{ $row->parent->parent_address ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Parent postcode:</div>
                <div class="col-7">{{ $row->parent->parent_postcode  ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Parent Stripe ID:</div>
                <div class="col-7">{{ $row->parent->stripe_customer_id ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold">Report details</h3>
            <div class="row">
                <div class="col-5 fw-bold">Session count:</div>
                <div class="col-7">{{ $row->session_count ?? '-' }}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-5 fw-bold">Question 1:</div>
                <div class="col-7">{{ $row->q1 ?? '-' }}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-5 fw-bold">Question 2:</div>
                <div class="col-7">{{ $row->q2 ?? '-' }}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-5 fw-bold">Question 3:</div>
                <div class="col-7">{{ $row->q3 ?? '-' }}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-5 fw-bold">Question 4:</div>
                <div class="col-7">{{ $row->q4 ?? '-' }}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-5 fw-bold">Rating:</div>
                <div class="col-7">{{ $row->tutor_review['rating'] ?? '-' }}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-5 fw-bold">Rating comment:</div>
                <div class="col-7">{{ $row->tutor_review['rating_comment'] ?? '-' }}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-5 fw-bold">Feedback received:</div>
                <div class="col-7">{{ $row->feedback_received ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-6">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="mb-2">
                        <label for="comment{{$row->id}}" class="form-label">Comment</label>
                        <textarea class="form-control" x-ref="comment{{$row->id}}" id="comment{{$row->id}}" rows="5"></textarea>
                    </div>
                    <input type="button" wire:click="addComment({{$row->id}}, $refs.comment{{$row->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="other-action">
                    <input type="button" value="Make not continuing" class="btn btn-info btn-sm w-25" x-on:click="function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Confirm session status change',
                                text: 'This will place this session to Not continuing. Are you sure about this?',
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    @this.call('makeSessionNotContinuing1', {{$row->session['id']}});
                                }
                            })}">
                        <input type="button" value="Make student inactive" data-bs-toggle="modal" data-bs-target="#makeStudentInactiveModal{{$row->child->id}}" class="btn btn-warning waves-effect waves-light btn-sm w-25">
                        <input type="button" value="Send to parent" wire:click="sendReportEmail({{$row->id}})" class="btn btn-secondary waves-effect btn-sm">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 history-detail">
            @forelse ($row->session->history as $item)
            <div class="mb-1">
                <div>{{ $item->comment}}</div>
                <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
            </div>
            @empty
            There are no any comments for this lead yet.
            @endforelse
        </div>
    </div>
    <div id="changeStatusModal{{$row->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Change status and follow up date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="followup_status{{$row->id}}" class="form-label">Choose an option</label>
                        <select name="followup_status{{$row->id}}" id="followup_status{{$row->id}}" x-ref="followup_status{{$row->id}}" class="form-select" required>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="followup_date{{$row->id}}" class="form-label">Session date</label>
                        <input type="text" class="form-control " name="followup_date{{$row->id}}" value="{{$row->followup_date}}" id="followup_date{{$row->id}}" x-ref="followup_date{{$row->id}}">
                    </div>
                </div>
                <div class="modal-footer" x-data="{ init() { 
                    $('#followup_date{{$row->id}}').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            todayHighlight: true,
                        });
                    } }">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="changeStatus({{$row->id}}, $refs.followup_status{{$row->id}}.value, $refs.followup_date{{$row->id}}.value)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <livewire:admin.components.make-student-inactive-modal child_id="{{$row->child->id}}" :key="$row->id" />
</div>