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
                        <input type="button" value="Send payment information email"  class="btn btn-outline-info waves-effect waves-light btn-sm w-50"  x-on:click="function() {
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
                        <input type="button" value="Send payment information SMS"  class="btn btn-outline-success waves-effect waves-light btn-sm w-50" x-on:click="function() {
                            Swal.fire({
                                icon: 'info',
                                title: 'Send payment information SMS',
                                text: 'This will fire an SMS to parent with their credit card submission page.',
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    @this.call('sendCcSMS1', {{$row->id}});
                                }
                            })}">
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
            There are no any comments for this parent yet.
            @endforelse
        </div>
    </div>
</div>