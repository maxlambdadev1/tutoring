<div>
    @php
    $title = "Session Types";
    $breadcrumbs = ["Owner", "Setting", "Session Types"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-session-alert />
                    <div class="text-end mb-2">
                        <a class="btn btn-primary mx-1" wire:click="openCreateSessionTypeModal" id="register_session_type">Register</a>
                    </div>
                    <div class="table-responsive-md">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Session price</th>
                                    <th>Tutor price</th>
                                    <th>Increase rate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sessionTypes as $type)
                                    <tr>
                                        <td>{{ (($sessionTypes->currentPage() - 1 ) * $sessionTypes->perPage() ) + $loop->iteration }}</td>
                                        <td>{{$type->name}}</td>
                                        <td>$&nbsp;{{$type->session_price}}</td>
                                        <td>$&nbsp;{{$type->tutor_price}}</td>
                                        <td>{{$type->increase_rate}}&nbsp;%</td>
                                        <td class="table-action">
                                            <a class="action-icon cursor-pointer" wire:click="openEditSessionTypeModal({{ $type }})" title="Edit"> <i class="mdi mdi-pencil"></i></a>
                                            <a class="action-icon cursor-pointer" wire:click="deleteSessionType({{ $type }})" title="Remove"> <i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Not exist any session types</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix mt-2">
                        <div class="float-left" style="margin: 0;">
                            <p>Total <strong style="color: red">{{ $sessionTypes->total() }}</strong></p>
                        </div>
                        <div class="float-right" style="margin: 0;">
                            {{ $sessionTypes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Type modal -->
    @if ($showSessionTypeModal)
    <div id="subject-modal" class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{ $editSessionTypeId ? "Edit Session type" : "Add Session Type"}}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetValues"></button>
                </div>
                <div class="modal-body">
                    <form action="#" class="">
                        <div class="row">
                            <div class="col-md-6">
                                <x-form-input wire:model="name" name="name" label="Name" placeholder="Name" type="text" />
                                <x-form-input wire:model="session_price" name="session_price" label="Session Price" placeholder="Session Price" type="text" />
                            </div>
                            <div class="col-md-6">
                                <x-form-input wire:model="tutor_price" name="tutor_price" label="Tutor Price" placeholder="Tutor Price" type="text" />
                                <x-form-input wire:model="increase_rate" name="increase_rate" label="Increase Rate" placeholder="Increase Rate" type="text" />
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <button class="btn btn-primary" id="save_session_type" type="button" wire:click="saveSessionType">Submit</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
