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
            <div class="row mb-2">
                <div class="col-12">
                    <div class="other-action">
                        <input type="button" value="Details" data-bs-toggle="modal" data-bs-target="#updateStudentDetailModal{{$row->id}}" class="btn btn-info btn-sm">
                        <input type="button" value="Send payment information email" class="btn btn-success btn-sm"  x-on:click="function() {
                            Swal.fire({
                                icon: 'info',
                                title: 'Send payment information email',
                                text: 'This will fire an email to parent with their credit card submission page.',
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    @this.call('sendCcEmail1', {{$row->id}});
                                }
                            })}">
                            @if ($row->child_status == 1)
                            <input type="button" value="Make inactive" data-bs-toggle="modal" data-bs-target="#makeStudentInactiveModal{{$row->id}}" class="btn btn-secondary btn-sm">
                            @else
                            <input type="button" value="Make active" wire:click="makeStudentActive({{$row->id}})" class="btn btn-primary btn-sm">
                            @endif
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <livewire:admin.components.change-session-type child_id="{{$row->id}}" key="a{{$row->id}}" />
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
            There are no any comments for this student yet.
            @endforelse
            @if ($row->child_status != 1)
            <div><span class="fw-bold">Follow Up Category: </span>{{$options['followup_category_for_student'][$row->follow_up] ?? '-'}}</div>
                @if(empty($row->no_follow_up_reason))
                <div><span class="fw-bold">Follow Up: </span>Yes</div>
                @else 
                <div><span class="fw-bold">Follow Up: </span>No</div>
                <div><span class="fw-bold">Reason: </span>{{$row->no_follow_up_reason}}</div>
                @endif
            @endif
        </div>
    </div>
    <div id="updateStudentDetailModal{{$row->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Update student information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="child_name{{$row->id}}" class="form-label">Student name</label>
                        <input type="text" class="form-control " name="child_name{{$row->id}}" value="{{$row->child_name ?? '-'}}" id="child_name{{$row->id}}" x-ref="child_name{{$row->id}}">
                    </div>
                    <div class="mb-3">
                        <label for="parent_name{{$row->id}}" class="form-label">Parent name</label>
                        <input type="text" class="form-control " name="parent_name{{$row->id}}" value="{{$row->parent->parent_name ?? '-'}}" id="parent_name{{$row->id}}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="child_school{{$row->id}}" class="form-label">School</label>
                        <input type="text" class="form-control " name="child_school{{$row->id}}" value="{{$row->child_school ?? '-'}}" id="child_school{{$row->id}}" x-ref="child_school{{$row->id}}">
                    </div>
                    <div class="mb-3">
                        <label for="child_year{{$row->id}}" class="form-label">Year</label>
                        <input type="text" class="form-control " name="child_year{{$row->id}}" value="{{$row->child_year ?? '-'}}" id="child_year{{$row->id}}" x-ref="child_year{{$row->id}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="updateStudentDetail({{$row->id}}, $refs.child_name{{$row->id}}.value, $refs.child_school{{$row->id}}.value, $refs.child_year{{$row->id}}.value)" data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <livewire:admin.components.make-student-inactive-modal child_id="{{$row->id}}" :key="$row->id" />
</div>