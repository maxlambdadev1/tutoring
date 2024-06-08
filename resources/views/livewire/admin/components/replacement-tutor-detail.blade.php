<div class="container mx-0">
    <div class="row">
        <div class="col-5">
            <h3 class="fw-bold">Student details</h3>
            <div class="row">
                <div class="col-md-6 fw-bold">Student school:</div>
                <div class="col-md-6">{{ $row->child->child_school }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Student grade:</div>
                <div class="col-md-6">{{ $row->child->child_year }}</div>
            </div>
            <h3 class="fw-bold">Parent details</h3>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent email:</div>
                <div class="col-md-6">{{ $row->parent->parent_email }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent phone:</div>
                <div class="col-md-6">{{ $row->parent->parent_phone }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent address:</div>
                <div class="col-md-6">{{ $row->parent->parent_address }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent postcode:</div>
                <div class="col-md-6">{{ $row->parent->parent_postcode }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent Stripe ID:</div>
                <div class="col-md-6">{{ $row->parent->stripe_customer_id }}</div>
            </div>
            <h3 class="fw-bold">Tutor details</h3>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor email:</div>
                <div class="col-md-6">{{ $row->tutor->user->email }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor phone:</div>
                <div class="col-md-6">{{ $row->tutor->tutor_phone }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor address:</div>
                <div class="col-md-6">{{ $row->tutor->address }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor postcode:</div>
                <div class="col-md-6">{{ $row->tutor->postcode }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor Stripe ID:</div>
                <div class="col-md-6">{{ $row->tutor->tutor_stripe_user_id ?? '-' }}</div>
            </div>
        </div>
        <div class="col-7">
            <h3 class="fw-bold">Replacement links</h3>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor link:</div>
                <div class="col-md-6">
                    <a href="https://alchemy.team/replacement-tutor?key={{$row->tutor_link}}">Tutor link</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent link:</div>
                <div class="col-md-6">
                    <a href="https://alchemy.team/replacement-tutor?key={{$row->parent_link}}">Parent link</a>
                </div>
            </div>
            <h3 class="fw-bold">Replacement tutor details</h3>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor suggested date:</div>
                <div class="col-md-6">{{ $row->tutor_last_session ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor notes:</div>
                <div class="col-md-6">{{ $row->tutor_notes ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent suggested day:</div>
                <div class="col-md-6">{{ $row->parent_day ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent notes:</div>
                <div class="col-md-6">{{ $row->parent_notes ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Replacement tutor email:</div>
                <div class="col-md-6">{{ $row->replacement_tutor->user->email ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Replacement tutor phone:</div>
                <div class="col-md-6">{{ $row->replacement_tutor->tutor_phone ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Replacement tutor address:</div>
                <div class="col-md-6">{{ $row->replacement_tutor->address ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Replacement tutor postcode:</div>
                <div class="col-md-6">{{ $row->replacement_tutor->postcode ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Replacement tutor Stripe ID:</div>
                <div class="col-md-6">{{ $row->replacement_tutor->tutor_stripe_user_id ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-7">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="mb-2">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" x-ref="comment{{$row->id}}" id="comment" rows="3"></textarea>
                    </div>
                    <input type="button" wire:click="addComment({{$row->child->id}}, $refs.comment{{$row->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                </div>
            </div>
            <!-- <div class="row">
                        <div class="col-12">
                            <div class="other-action">
                                <input type="button" value="Add replacement lead" class="btn btn-primary btn-sm">
                                <input type="button" value="Change status" data-bs-toggle="modal" data-bs-target="#changeStatusModal{{$row->id}}"  class="btn btn-warning btn-sm">
                                <input type="button" value="Send tutor email" data-bs-toggle="modal" data-bs-target="#assignLeadModal{{$row->id}}" class="btn btn-info btn-sm">
                                <input type="button" value="Send parent email" data-bs-toggle="modal" data-bs-target="#editLeadModal{{$row->id}}" class="btn btn-success btn-sm">
                                <input type="button" value="Delete replacement" class="btn btn-secondary btn-sm">
                            </div>
                        </div>
                    </div> -->
        </div>
        <div class="col-5 history-detail">
            @forelse ($row->child->history as $item)
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
                        <label for="option{{$row->id}}" class="form-label">Choose an option</label>
                        <select name="option{{$row->id}}" id="option{{$row->id}}" x-ref="option{{$row->id}}" class="form-select">
                            <option value="">All</option>
                            <option value="1">Uncategoriezed</option>
                            <option value="2">Waiting to hear back from tutor</option>
                            <option value="3">Waiting to hear back from parent</option>
                            <option value="4">Disregard</option>
                            <option value="5">Monitor</option>
                            <option value="6">Rescue</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="followup_date{{$row->id}}" class="form-label">Follow up date</label>
                        <input type="text" class="form-control " name="followup_date{{$row->id}}" id="followup_date{{$row->id}}" x-ref="followup_date{{$row->id}}">
                    </div>
                </div>
                <div class="modal-footer" x-data="{ init() { 
                    $('#followup_date{{$row->id}}').datepicker({
                            autoclose: true,
                            format: 'dd/mm/yyyy',
                            startDate: new Date(),
                            todayHighlight: true,
                        });
                    } }">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="deleteLead1({{$row->id}}, $refs.option{{$row->id}}.value, $refs.followup_date{{$row->id}}.value)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>