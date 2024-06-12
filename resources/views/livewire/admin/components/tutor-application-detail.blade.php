<div class="container mx-0">
    <livewire:admin.components.application-description :app_id="$row->id" :key="$row->id" />

    <div class="row">
        <div class="col-6">
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
                <div class="col-3">
                    <div>
                        <select class="form-select form-select-sm" id="app-status" x-ref="app_status{{$row->id}}">
                            <option value="1" @if ($row->application_status == '1') 'selected' @endif >Applied as tutor</option>
                            <option value="7" @if ($row->application_status == '7') 'selected' @endif >Auto-reject</option>
                            <option value="2" @if ($row->application_status == '2') 'selected' @endif >Scheduling interview</option>
                            <option value="3" @if ($row->application_status == '3') 'selected' @endif >Interview scheduled</option>
                            <option value="4" @if ($row->application_status == '4') 'selected' @endif >Awaiting to register</option>
                            <option value="5" @if ($row->application_status == '5') 'selected' @endif >Registered</option>
                            <option value="6" @if ($row->application_status == '6') 'selected' @endif >Rejected</option>
                            <option value="9" @if ($row->application_status == '9') 'selected' @endif >Closed</option>
                        </select>
                    </div>
                </div>
                <div class="col-9">
                    <div class="other-action">
                        <input type="button" value="Update status" wire:click="acceptTutorApplication1({{$row->id}}, $refs.app_status{{$row->id}}.value)" class="btn btn-info btn-sm">
                        <input type="button" value="Reject tutor" wire:click="rejectTutorApplication({{$row->id}})" class="btn btn-success btn-sm">
                        <input type="button" value="Match hot lead" class="btn btn-secondary btn-sm">
                        <input type="button" value="Delete application"  x-on:click="function() {
                            Swal.fire({
                                icon: 'info',
                                title: 'Delete application',
                                text: 'You are about to delete this application - This cannot be undone.',
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    @this.call('deleteTutorApplication', {{$row->id}});
                                }
                            })}"  class="btn btn-danger btn-sm">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 history-detail">
            @forelse ($row->history as $comment)
            <div class="mb-1">
                <div>{{ $comment->comment}}</div>
                <span class="text-muted"><small>{{ $comment->author }} on {{ $comment->date }}</small></span>
            </div>
            @empty
            There are no any comments for this application yet.
            @endforelse
        </div>
    </div>
</div>