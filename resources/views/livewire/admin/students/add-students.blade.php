<div>
    @php
    $title = "Add Students";
    $breadcrumbs = ["Students", "Add"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body" x-data="{is_existing : true, showDropdown : false }">
                    <x-session-alert />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_type" class="form-label">Select option</label>
                                <select name="parent_type" id="parent_type" class="form-select " wire:model="parent_type" x-on:change="is_existing = $event.target.value == 'existing'">
                                    <option value="existing">Existing parent</option>
                                    <option value="new">New parent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" x-show="is_existing">
                            <div class="mb-3">
                                <label class="form-label" for="parent_str">Select parent</label>
                                <div class="input-group" id="dropdown-toggle" data-bs-toggle="dropdown">
                                    <input wire:model="parent_str" id="parent_str" name="parent_str" wire:keydown="searchParentsByName" x-on:focus="showDropdown = true" x-on:blur="setTimeout(() => showDropdown = false, 200)" type="text" class="form-control" placeholder="Type here to find the parents (Name or Email)...">
                                </div>
                                <ul class="dropdown-menu dropdown-menu-md p-2" :class="{'d-block show' : showDropdown}">
                                    @forelse ($searched_parents as $parent)
                                    <li class="cursor-pointer" wire:click="selectParent({{ $parent->id }})" x-on:click="showDropdown = false">
                                        <a class="dropdown-item">
                                            <div>{{ $parent->parent_first_name}} {{$parent->parent_last_name}}</div>
                                            <span>{{ $parent->parent_email }}</span>
                                        </a>
                                    </li>
                                    @empty
                                    <li class="cursor-pointer">
                                        <a class="dropdown-item">
                                            <span>There are no parents</span>
                                        </a>
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row" x-show="is_existing == false">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_first_name" class="form-label">Parent first name</label>
                                <input type="text" class="form-control " name="parent_first_name" id="parent_first_name" wire:model="parent_first_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_last_name" class="form-label">Parent last name</label>
                                <input type="text" class="form-control " name="parent_last_name" id="parent_last_name" wire:model="parent_last_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_phone" class="form-label">Parent phone</label>
                                <input type="text" class="form-control " name="parent_phone" id="parent_phone" wire:model="parent_phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_email" class="form-label">Parent email</label>
                                <input type="text" class="form-control " name="parent_email" id="parent_email" wire:model="parent_email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_address" class="form-label">Parent address</label>
                                <input type="text" class="form-control " name="parent_address" id="parent_address" wire:model="parent_address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_suburb" class="form-label">Parent suburb</label>
                                <input type="text" class="form-control " name="parent_suburb" id="parent_suburb" wire:model="parent_suburb">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_postcode" class="form-label">Parent postcode</label>
                                <input type="text" class="form-control " name="parent_postcode" id="parent_postcode" wire:model="parent_postcode">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="child_first_name" class="form-label">Student first name</label>
                                <input type="text" class="form-control " name="child_first_name" id="child_first_name" wire:model="child_first_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="child_last_name" class="form-label">Student last name</label>
                                <input type="text" class="form-control " name="child_last_name" id="child_last_name" wire:model="child_last_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="child_school" class="form-label">Student school</label>
                                <input type="text" class="form-control " name="child_school" id="child_school" wire:model="child_school">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="child_year" class="form-label">Student Grade</label>
                                <select name="child_year" id="child_year" class="form-select " wire:model="child_year">
                                    @foreach ($grades as $grade)
                                    <option value="{{$grade->name}}">{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm" wire:click="addStudent">Add student</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>