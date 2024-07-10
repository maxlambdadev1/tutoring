<div>
    @php
    $title = "Add session";
    $breadcrumbs = ["Sessions", "Add"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-session-alert />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3" x-data="{showDropdown: false}">
                                <label class="form-label" for="child_id">Select student</label>
                                <select name="child_id" id="child_id" class="form-select " wire:model="child_id" wire:change="getPreviousSession">
                                    @forelse ($students as $child)
                                    <option value="{{$child->id}}">{{$child->child_name}}</option>
                                    @empty
                                    <option value="">There are no students</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                    @php $isCapable = false; @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                @if (!empty($prev_session))
                                @if ($prev_session->session_is_first == 1 && $prev_session->session_status == 4)
                                <div class="alert alert-danger" role="alert">
                                    We are currently awaiting confirmation from this student's parents of your second session. If you have confirmed it with them directly please let us know and we can add it for you.
                                </div>
                                @elseif ($prev_session->session_status == 1 || $prev_session->session_status == 3)
                                <div class="alert alert-danger" role="alert">
                                    You already have a scheduled session with this student. Please confirm it first.
                                </div>
                                @else
                                @php $isCapable = true; @endphp
                                <label for="prev_session_id" class="form-label">Select previous session</label>
                                <select name="prev_session_id" id="prev_session_id" class="form-select " wire:model="prev_session_id" x-data>
                                    @if (!empty($prev_session->session_length))
                                    <option value="{{$prev_session->id}}">{{$prev_session->session_length }} hour(s) session on {{$prev_session->date}} at {{$prev_session->session_time_ampm}}</option>
                                    @else
                                    <option value="{{$prev_session->id}}">Canceled session on {{$prev_session->date}} at {{$prev_session->session_time_ampm}}</option>
                                    @endif
                                </select>
                                @endif
                                @else
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($isCapable)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="session_date" class="form-label">Session date</label>
                                <input type="text" class="form-control " name="session_date" id="session_date" x-ref="session_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="session_time" class="form-label">Session time</label>
                                <input type="text" class="form-control " name="session_time" id="session_time" x-ref="session_time">
                            </div>
                        </div>
                    </div>
                    <div class="text-center" x-data="{ init() { 
                        $('#session_date').datepicker({
                                autoclose: true,
                                format: 'dd/mm/yyyy',
                                todayHighlight: true,
                            });
                        $('#session_time').datetimepicker({
                            format: 'LT',                       
                        })
                        }}">
                        <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm" wire:click="addSession1($refs.session_date.value, $refs.session_time.value)">Add session</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>