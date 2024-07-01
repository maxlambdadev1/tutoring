<div>
    <div class="row mt-3">
        <div class="col-7">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="mb-2">
                        <label for="rating_comment{{$row->id}}" class="form-label">Rating comment</label>
                        <textarea class="form-control" x-ref="rating_comment{{$row->id}}" id="rating_comment{{$row->id}}" rows="5">{{$row->rating_comment}}</textarea>
                    </div>
                    <input type="button" wire:click="tutorReviewUpdate({{$row->id}}, $refs.rating_comment{{$row->id}}.value)" value="Update" class="btn btn-primary btn-sm form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="other-action">
                        @if ($options['status'] == 'pending')
                        <input type="button" value="Approve" wire:click="tutorReviewApprove({{$row->id}})" class="btn btn-info btn-sm w-25">
                        <input type="button" value="Reject" wire:click="tutorReviewReject({{$row->id}})" class="btn btn-warning btn-sm w-25">
                        @elseif($options['status'] == 'rejected')
                        <input type="button" value="Approve" wire:click="tutorReviewApprove({{$row->id}})" class="btn btn-info btn-sm w-25">
                        @endif
                        <input type="button" value="Email only" wire:click="tutorReviewEmail({{$row->id}})" class="btn btn-secondary btn-sm w-25">
                        <input type="button" value="Hide" wire:click="tutorReviewHide({{$row->id}})" class="btn btn-success btn-sm w-25">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>