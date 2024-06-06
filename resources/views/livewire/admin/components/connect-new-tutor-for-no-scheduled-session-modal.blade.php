<div>
    <div id="connectNewTutorNoScheduledSessionModal{{$session->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Connnect new tutor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @if (!empty($tutors) && count($tutors) > 0)
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_tutor{{$session->id}}" class="form-label">Choose a tutor</label>
                        <select name="new_tutor{{$session->id}}" id="new_tutor{{$session->id}}" x-ref="new_tutor{{$session->id}}" class="form-select" required>
                            <option value="" >Please select</option>
                            @forelse ($tutors as $tutor)
                            <option value="{{$tutor->id}}" >{{$tutor->tutor_name}}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="modal-footer" >
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light"  wire:click="connectNewTutorToSession({{$session->id}}, $refs.new_tutor{{$session->id}}.value)" data-bs-dismiss="modal">Submit</button>
                </div>
                @else
                <div class="modal-body">
                    <div class="mb-3 text-center fw-bold">
                        There are no tutors.
                    </div>
                </div>
                <div class="modal-footer" >
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                </div>
                @endif
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>
