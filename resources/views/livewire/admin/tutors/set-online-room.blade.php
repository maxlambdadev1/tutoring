<div>
    @php
    $title = "Tutors who have not online classroom";
    $breadcrumbs = ["Tutors", "Set online room"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.tutors.set-online-room-table />
                </div>
            </div>
        </div>
    </div>

    
    <div id="setTutorRoomModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-modal="true" role="dialog" x-data="{is_now : true }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Set online room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="tutor_id" id="tutor_id" x-ref="tutor_id" />
                    <div class="mb-3">
                        <label for="online_url" class="form-label">Please input the online room url.</label>
                        <textarea class="form-control " name="online_url" id="online_url" x-ref="online_url" rows="5" ></textarea>
                    </div>
                </div>
                <div class="modal-footer" >
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" 
                        wire:click="setTutorOnlineRoom($refs.tutor_id.value, $refs.online_url.value)"
                        data-bs-dismiss="modal">Submit</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>

<script>    
    $(document).on('set-room-modal', event => {
        let data = event.detail[0];
        $('#tutor_id').val(data.tutor_id);
        $('#setTutorRoomModal').modal('show');
    })
</script>