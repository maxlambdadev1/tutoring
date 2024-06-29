<div>
    @php
    $title = "Add session";
    $breadcrumbs = ["Sessions", "Add"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" x-data="{is_first: true}">
                    <x-session-alert />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Session Regularity</label>
                                <select name="type" id="type" class="form-select " wire:model="type" wire:change="selectPrevSessionsAndSubjects" x-on:change="is_first = $event.target.value == 'first'">
                                    <option value="first">First session</option>
                                    <option value="second">Second session</option>
                                    <option value="regular">Regular session</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="session_type_id" class="form-label">Session type</label>
                                <select name="session_type_id" id="session_type_id" class="form-select " wire:model="session_type_id">
                                    <option value="1">Face to Face</option>
                                    <option value="2">Online</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3" x-data="{showDropdown: false}">
                                <label class="form-label" for="phone">Select tutor</label>
                                <div class="input-group" id="dropdown-toggle" data-bs-toggle="dropdown">
                                    <input wire:model="tutor_str" id="tutor_str" name="tutor_str" wire:keydown="searchTutorsByName" x-on:focus="showDropdown = true" x-on:blur="setTimeout(() => showDropdown = false, 200)" type="text" class="form-control" placeholder="Type here to find the tutors (Name or Email)...">
                                </div>
                                <ul class="dropdown-menu dropdown-menu-md p-2" :class="{'d-block show' : showDropdown}">
                                    @forelse ($searched_tutors as $tutor)
                                    <li class="cursor-pointer" wire:click="selectTutor({{ $tutor->id }})" x-on:click="showDropdown = false">
                                        <a class="dropdown-item">
                                            <div>{{ $tutor->tutor_name }}</div>
                                            <span>{{ $tutor->user->email }}</span>
                                        </a>
                                    </li>
                                    @empty
                                    <li class="cursor-pointer">
                                        <a class="dropdown-item">
                                            <span>There are no tutors</span>
                                        </a>
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3" x-data="{showDropdown: false}">
                                <label class="form-label" for="phone">Select child</label>
                                <div class="input-group" id="dropdown-toggle" data-bs-toggle="dropdown">
                                    <input wire:model="child_str" id="child_str" name="child_str" wire:keydown="searchChildrenByName" x-on:focus="showDropdown = true" x-on:blur="setTimeout(() => showDropdown = false, 200)" type="text" class="form-control" placeholder="Type here to find the children">
                                </div>
                                <ul class="dropdown-menu dropdown-menu-md p-2" :class="{'d-block show' : showDropdown}">
                                    @forelse ($searched_children as $child)
                                    <li class="cursor-pointer" wire:click="selectChild({{ $child->id }})" x-on:click="showDropdown = false">
                                        <a class="dropdown-item">
                                            <div>{{ $child->child_name }}</div>
                                            <span>Parent: {{$child->parent->parent_first_name . ' ' . $child->parent->parent_last_name }}</span>
                                        </a>
                                    </li>
                                    @empty
                                    <li class="cursor-pointer">
                                        <a class="dropdown-item">
                                            <span>There are no children</span>
                                        </a>
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div x-show="!is_first" class="col-md-6">
                            <div class="mb-3">
                                <label for="prev_session_id" class="form-label">Select previous session</label>
                                <select name="prev_session_id" id="prev_session_id" class="form-select " wire:model="prev_session_id">
                                    @forelse ($prev_sessions as $session)
                                    <option value="{{$session->id}}">{{$session->date}} at {{$session->time}} for {{$session->session_length }} hour(s)</option>
                                    @empty
                                    <option value="">There are no previou sessions</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subject" class="form-label">Session subject</label>
                                <select name="subject" id="subject" class="form-select " wire:model="subject">
                                    @forelse ($subjects as $subject)
                                    <option value="{{$subject->name}}">{{$subject->name}}</option>
                                    @empty
                                    <option value="">There are no subjects</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
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
                    <div x-show="!is_first">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cc-name" class="form-label">Parent CC name</label>
                                    <input type="text" class="form-control " name="cc-name" id="cc-name" value="" wire:model="cc_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cc-number" class="form-label">Parent CC number</label>
                                    <input type="text" class="form-control " name="cc-number" id="cc-number" value="" wire:model="cc_number">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div x-show="!is_first">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cc-expiry" class="form-label">Parent CC expiry</label>
                                    <input type="text" class="form-control " name="cc-expiry" id="cc-expiry" value="" wire:model="cc_expiry">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cc-cvc" class="form-label">Parent CC cvc</label>
                                    <input type="text" class="form-control " name="cc-cvc" id="cc-cvc" value="" wire:model="cc_cvc">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" x-data="{ init() { 
                        $('#session_date').datepicker({
                                autoclose: true,
                                format: 'dd/mm/yyyy',
                                todayHighlight: true,
                            }).on('changeDate', function (e) {    
                                var session_date = e.format(0, 'dd/mm/yyyy');
                                @this.set('session_date', session_date);   
                            });
                        $('#session_time').datetimepicker({
                            format: 'LT',                       
                        }).on('dp.change', function (e) { 
                               let time =  e.date.format('h:mm A'); console.log(e, time);
                               @this.set('session_time', time);   
                            });
                        } }">
                        <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm" wire:click="addSession1">Add session</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>