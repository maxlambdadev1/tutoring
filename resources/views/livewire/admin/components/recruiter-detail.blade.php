<div class="container mx-0">
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
                        <input type="button" value="View/Edit details" data-bs-toggle="modal" data-bs-target="#editRecruiterModal{{$row->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm w-50">
                        <input type="button" value="Make inactive" data-bs-toggle="modal" data-bs-target="#makeRecruiterInactiveModal{{$row->id}}" class="btn btn-outline-secondary waves-effect waves-light btn-sm w-50">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 history-detail">
            @forelse ($row->history as $item)
            <div class="mb-1">
                <div>{{ $item->comment}}</div>
                <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
            </div>
            @empty
            There are no any comments for this recruiter yet.
            @endforelse
        </div>
    </div>
    <div id="editRecruiterModal{{$row->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">
                        {{$row->first_name}}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email{{$row->id}}" class="form-label">Email</label>
                            <input type="text" class="form-control " disabled name="email{{$row->id}}" value="{{$row->email}}" id="email{{$row->id}}" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone{{$row->id}}" class="form-label">Phone</label>
                            <input type="text" class="form-control " name="phone{{$row->id}}" value="{{$row->phone}}" id="phone{{$row->id}}" x-ref="phone{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="suburb{{$row->id}}" class="form-label">Suburb</label>
                            <input type="text" class="form-control " name="suburb{{$row->id}}" value="{{$row->suburb}}" id="suburb{{$row->id}}" x-ref="suburb{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state{{$row->id}}" class="form-label">State</label>
                            <input type="text" class="form-control " name="state{{$row->id}}" value="{{$row->state}}" id="state{{$row->id}}" x-ref="state{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ABN{{$row->id}}" class="form-label">ABN</label>
                            <input type="text" class="form-control " name="ABN{{$row->id}}" value="{{$row->ABN}}" id="ABN{{$row->id}}" x-ref="ABN{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bsb{{$row->id}}" class="form-label">BSB</label>
                            <input type="text" class="form-control " name="bsb{{$row->id}}" value="{{$row->bsb}}" id="bsb{{$row->id}}" x-ref="bsb{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bank_account_number{{$row->id}}" class="form-label">Bank account number</label>
                            <input type="text" class="form-control " name="bank_account_number{{$row->id}}" value="{{$row->bank_account_number}}" id="bank_account_number{{$row->id}}" x-ref="bank_account_number{{$row->id}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="postcode{{$row->id}}" class="form-label">Postcode</label>
                            <input type="text" class="form-control " name="postcode{{$row->id}}" value="{{$row->postcode}}" id="postcode{{$row->id}}" x-ref="postcode{{$row->id}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="updateRecruiter({{$row->id}}, $refs.phone{{$row->id}}.value, $refs.suburb{{$row->id}}.value, $refs.state{{$row->id}}.value, $refs.ABN{{$row->id}}.value, $refs.bsb{{$row->id}}.value, $refs.bank_account_number{{$row->id}}.value, $refs.postcode{{$row->id}}.value)" data-bs-dismiss="modal">Update</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div id="makeRecruiterInactiveModal{{$row->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">
                        Make inactive
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="reason{{$row->id}}" class="form-label">Please add a reason</label>
                            <textarea type="text" class="form-control " name="reason{{$row->id}}" rows="5" id="reason{{$row->id}}" x-ref="reason{{$row->id}}"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="makeRecruierInactive({{$row->id}}, $refs.reason{{$row->id}}.value)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>