<div>
    @php
    $title = "State & Grade";
    $breadcrumbs = ["Owner", "Setting", "State&Grade"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <x-session-alert />
            <div class="card mb-3">
                <div class="card-body">
                    <h3>State</h3>
                    <form wire:submit.prevent="createState" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <x-form-input wire:model="state_name" type="text" name="state_name" label="Name" placeholder="Name" autocomplete="state_name" />
                            </div>
                            <div class="col-md-4">
                                <x-form-input wire:model="description" type="text" name="description" label="Description" placeholder="Description" autocomplete="description" />
                            </div>
                            <div class="col-md-4 d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <hr>

                    <div class="table-responsive-md">
                        <table class="table table-centered table-hover mb-0">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($states as $state)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td class="state_name">{{$state->name}}</td>
                                    <td class="state_desc">{{$state->description}}</td>
                                    <td class="table-action">
                                        <a class="action-icon state_edit cursor-pointer" title="Edit" wire:click="openEditStateModal({{ $state->id }})"> <i class="mdi mdi-pencil"></i></a>
                                        <a class="action-icon destroy cursor-pointer" title="Remove"> <i class="mdi mdi-delete" wire:click="deleteState({{ $state }})"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr class="text-center">
                                    <td colspan="4">Not exist any states.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h3>Grade</h3>
                    <form wire:submit.prevent="createGrade" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <x-form-input wire:model="gradeName" type="text" name="gradeName" label="Name" placeholder="Name" autocomplete="gradeName" />
                            </div>
                            <div class="col-md-4 d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <hr>

                    <div class="table-responsive-md">
                        <table class="table table-centered table-hover mb-0">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($grades as $grade)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td class="gradeName">{{$grade->name}}</td>
                                    <td class="table-action">
                                        <a class="action-icon grade_edit cursor-pointer" title="Edit" wire:click="openEditGradeModal({{ $grade }})"> <i class="mdi mdi-pencil"></i></a>
                                        <a class="action-icon destroy cursor-pointer" title="Remove"> <i class="mdi mdi-delete" wire:click="deleteGrade({{ $grade }})"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr class="text-center">
                                    <td colspan="4">Not exist any grades.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- State modal -->
    <div id="state-modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit State</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="reset_state_values"></button>
                </div>
                <div class="modal-body">
                    <form action="#" class="ps-3 pe-3">
                        <x-form-input wire:model="editStateName" name="editStateName" label="Name" placeholder="Name" type="text" />
                        <x-form-input wire:model="editStateDesc" name="editStateDesc" label="Description" placeholder="Description" type="text" />
                        <div class="mb-3 text-center">
                            <button class="btn rounded-pill btn-primary" data-bs-dismiss="modal" id="update_state" type="button" wire:click="updateState">Update</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Grade modal -->
    <div id="grade-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Grade</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="reset_grade_values"></button>
                </div>
                <div class="modal-body">
                    <form action="#" class="ps-3 pe-3">
                        <x-form-input wire:model="editGradeName" name="editGradeName" label="Name" placeholder="Name" type="text" />
                        <div class="mb-3 text-center">
                            <button class="btn rounded-pill btn-primary" data-bs-dismiss="modal" id="update_grade" type="button" wire:click="updateGrade">Update</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

@script
<script>
    $wire.on('openEditStateModal', data => {
        $('#state-modal').modal('show');
    })
    $wire.on('openEditGradeModal', data => {
        $('#grade-modal').modal('show');
    })
</script>
@endscript