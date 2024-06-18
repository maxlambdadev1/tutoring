<div class="row">
    <div class="col-6">
        <select class="form-select" id="select-tutor{{$child->id}}" wire:model="selected_tutor_id" wire:change="changeTutor">
            @if (!empty($jobs) && count($jobs) > 0)
            <option>Please select</option>
            @foreach ($jobs as $job)
            <option value="{{$job->tutor->id}}">{{$job->tutor->tutor_email}}</option>
            @endforeach
            @else
            <option>There are no tutors to select</option>
            @endif
        </select>
    </div>
    @if (!empty($selected_job))
    <div class="col-6 pt-2">
        <div class="form-check form-check-inline">
            <input type="radio" id="session_type_f2f{{$child->id}}" name="session_type{{$child->id}}" class="form-check-input" {{$selected_job->session_type_id == 1 ? 'checked' : '' }}
                wire:change="changeSessionType(1)">
            <label class="form-check-label" for="session_type_f2f{{$child->id}}">F2F</label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio" id="session_type_online{{$child->id}}" name="session_type{{$child->id}}" class="form-check-input" {{$selected_job->session_type_id == 2 ? 'checked' : '' }}
                wire:change="changeSessionType(2)">
            <label class="form-check-label" for="session_type_online{{$child->id}}">Online</label>
        </div>
    </div>
    @endif
</div>
