<div class="container mx-0">
    <div class="row">
        <div class="col-6">
            <h3 class="fw-bold">Reference 1</h3>
            <div class="row">
                <div class="col-5 fw-bold">Reference 1 First Name:</div>
                <div class="col-7">{{$row->tutor_fname1 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 1 Last Name:</div>
                <div class="col-7">{{$row->tutor_lname1 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 1 Email:</div>
                <div class="col-7">{{$row->tutor_email1 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 1 Relationship:</div>
                <div class="col-7">{{$row->tutor_relation1 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 1 status:</div>
                <div class="col-7">
                    @if (!empty($row->tutor_reference1))
                    @if ($row->tutor_reference1->reason == 'ok')
                    <span class="badge bg-success">Yes</span>
                    @else
                    <span class="badge bg-danger">No</span> ({{$row->tutor_reference1->reason}})
                    @endif
                    @else
                    <span class="badge bg-warning">Wait</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-6">
            <h3 class="fw-bold">Reference 2</h3>
            <div class="row">
                <div class="col-5 fw-bold">Reference 2 First Name:</div>
                <div class="col-7">{{$row->tutor_fname2 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 2 Last Name:</div>
                <div class="col-7">{{$row->tutor_lname2 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 2 Email:</div>
                <div class="col-7">{{$row->tutor_email2 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 2 Relationship:</div>
                <div class="col-7">{{$row->tutor_relation2 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 2 status:</div>
                <div class="col-7">
                    @if (!empty($row->tutor_reference2))
                    @if ($row->tutor_reference2->reason == 'ok')
                    <span class="badge bg-success">Yes</span>
                    @else
                    <span class="badge bg-danger">No</span> ({{$row->tutor_reference2->reason}})
                    @endif
                    @else
                    <span class="badge bg-warning">Wait</span>
                    @endif
                </div>
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
                        <input type="button" value="Edit reference 1" data-bs-toggle="modal" data-bs-target="#editReferenceModal1{{$row->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm w-50">
                        <input type="button" value="Edit reference 2" data-bs-toggle="modal" data-bs-target="#editReferenceModal2{{$row->id}}" class="btn btn-outline-success waves-effect waves-light btn-sm w-50">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 history-detail">
            @forelse ($row->reference_history as $item)
            <div class="mb-1">
                <div>{{ $item->comment}}</div>
                <span class="text-muted"><small>{{ $item->author }} on {{ $item->created_at }}</small></span>
            </div>
            @empty
            There are no any comments for this application reference yet.
            @endforelse
        </div>
    </div>
    <div id="editReferenceModal1{{$row->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">
                        Edit Reference 1
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">                            
                            <div class="alert alert-info" role="alert">
                                Note: If you wanna send email to new reference, please do it after update old reference to new reference.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="first_name1{{$row->id}}" class="form-label">Reference first name</label>
                            <input type="text" class="form-control " name="first_name1{{$row->id}}" value="{{$row->tutor_fname1}}" id="first_name1{{$row->id}}" x-ref="first_name1{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name1{{$row->id}}" class="form-label">Reference last name</label>
                            <input type="text" class="form-control " name="last_name1{{$row->id}}" value="{{$row->tutor_lname1}}" id="last_name1{{$row->id}}" x-ref="last_name1{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email1{{$row->id}}" class="form-label">Reference email</label>
                            <input type="text" class="form-control " name="email1{{$row->id}}" value="{{$row->tutor_email1}}" id="email1{{$row->id}}" x-ref="email1{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="relation1{{$row->id}}" class="form-label">Reference relation</label>
                            <select name="relation1{{$row->id}}" id="relation1{{$row->id}}" class="form-select form-select-sm" x-ref="relation1{{$row->id}}">
                                <option value=""></option>
                                <option value="Family member" @if ($row->tutor_relation1 == "Family member") selected @endif>Family member</option>
                                <option value="Friend" @if ($row->tutor_relation1 == "Friend") selected @endif>Friend</option>
                                <option value="Former teacher" @if ($row->tutor_relation1 == "Former teacher") selected @endif>Former teacher</option>
                                <option value="Employer or colleague" @if ($row->tutor_relation1 == "Employer or colleague") selected @endif>Employer or colleague</option>
                                <option value="Other" @if ($row->tutor_relation1 == "Other") selected @endif>Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="updateReference1({{$row->id}}, $refs.first_name1{{$row->id}}.value, $refs.last_name1{{$row->id}}.value, $refs.email1{{$row->id}}.value, $refs.relation1{{$row->id}}.value)" data-bs-dismiss="modal">Update</button>
                    <button type="button" class="btn btn-info waves-effect waves-light" wire:click="emailToReference1({{$row->id}})" data-bs-dismiss="modal">Email to reference</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div id="editReferenceModal2{{$row->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">
                        Edit Reference 2
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">                            
                            <div class="alert alert-info" role="alert">
                                Note: If you wanna send email to new reference, please do it after update old reference to new reference.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="first_name2{{$row->id}}" class="form-label">Reference first name</label>
                            <input type="text" class="form-control " name="first_name2{{$row->id}}" value="{{$row->tutor_fname2}}" id="first_name2{{$row->id}}" x-ref="first_name2{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name2{{$row->id}}" class="form-label">Reference last name</label>
                            <input type="text" class="form-control " name="last_name2{{$row->id}}" value="{{$row->tutor_lname2}}" id="last_name2{{$row->id}}" x-ref="last_name2{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email2{{$row->id}}" class="form-label">Reference email</label>
                            <input type="text" class="form-control " name="email2{{$row->id}}" value="{{$row->tutor_email2}}" id="email2{{$row->id}}" x-ref="email2{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="relation2{{$row->id}}" class="form-label">Reference relation</label>
                            <select name="relation2{{$row->id}}" id="relation2{{$row->id}}" class="form-select form-select-sm" x-ref="relation2{{$row->id}}">
                                <option value=""></option>
                                <option value="Family member" @if ($row->tutor_relation2 == "Family member") selected @endif>Family member</option>
                                <option value="Friend" @if ($row->tutor_relation2 == "Friend") selected @endif>Friend</option>
                                <option value="Former teacher" @if ($row->tutor_relation2 == "Former teacher") selected @endif>Former teacher</option>
                                <option value="Employer or colleague" @if ($row->tutor_relation2 == "Employer or colleague") selected @endif>Employer or colleague</option>
                                <option value="Other" @if ($row->tutor_relation2 == "Other") selected @endif>Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="updateReference2({{$row->id}}, $refs.first_name2{{$row->id}}.value, $refs.last_name2{{$row->id}}.value, $refs.email2{{$row->id}}.value, $refs.relation2{{$row->id}}.value)" data-bs-dismiss="modal">Update</button>
                    <button type="button" class="btn btn-info waves-effect waves-light" wire:click="emailToReference2({{$row->id}})" data-bs-dismiss="modal">Email to reference</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>