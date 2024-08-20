<div>
    <button class="btn btn-danger rounded btn-sm"  data-bs-toggle="modal" data-bs-target="#rescheduleSessionModal{{$row->id}}">Reschedule</button>
    <livewire:tutor.components.reschedule-session-modal session_id="{{$row->id}}" key="{{$row->id}}" />
</div>