<div>
    <div class="row">
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
            <div class="other-action">
                <input type="button" wire:click="removeHolidayStudentRecord({{$row->id}})" value="Remove record" class="btn btn-secondary waves-effect waves-light btn-sm">
                <input type="button" wire:click="moveHolidayReplacement1({{$row->id}})" value="Move replacement list" class="btn btn-info waves-effect waves-light btn-sm">
                <input type="button" value="Make student inactive" data-bs-toggle="modal" data-bs-target="#makeStudentInactiveModal{{$row->child->id}}" class="btn btn-warning btn-sm w-25">
                <div class="d-flex">
                    <span class="form-label fw-bold">Reach out to parent &nbsp;</span>
                    <div>
                        <input type="checkbox" id="reach_out_parent{{$row->id}}" name="reach_out_parent{{$row->id}}" data-switch="success" {{ !empty($row->reach_parent) ? 'checked' : ''}} wire:click="holidayReachParent({{$row->id}})">
                        <label for="reach_out_parent{{$row->id}}" data-on-label="Yes" data-off-label="No"></label>
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
            There are no any comments for this holiday student yet.
            @endforelse
        </div>
    </div>
    <livewire:admin.components.make-student-inactive-modal child_id="{{$row->child->id}}" :is_holiday_student="true" :key="$row->id" />
</div>