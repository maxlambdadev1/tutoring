<div>
    @if ($row->session_status == 1)
    <a href="/sessions/{{$row->id}}/confirm" class="btn btn-success btn-sm" >Confirm</a>
    @endif
    <button class="btn btn-danger rounded btn-sm"  data-bs-toggle="modal" data-bs-target="#rescheduleSessionModal{{$row->id}}">Reschedule</button>
    <livewire:tutor.components.reschedule-session-modal session_id="{{$row->id}}" key="{{$row->id}}" />
</div>