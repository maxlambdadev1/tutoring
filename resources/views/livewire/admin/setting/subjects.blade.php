<div>
    @php
    $title = "Subjects";
    $breadcrumbs = ["Owner", "Setting", "Subjects"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-session-alert />
                    <div class="text-end mb-2">
                        <a class="btn btn-primary mx-1" wire:click="openCreateSubjectModal" id="add_subjects">Add Subject</a>
                        <a class="btn btn-danger" id="reset_subjects" onclick="resetSubjects()">Reset Subjects</a>
                    </div>
                    <div class="table-responsive-md">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>State</th>
                                    <th>Grades</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subjects as $subject)
                                <tr>
                                    <td>{{ (($subjects->currentPage() - 1 ) * $subjects->perPage() ) + $loop->iteration }}</td>
                                    <td>{{$subject->state->name}}</td>
                                    <td>{{$subject->getGradesName()}}</td>
                                    <td>{{$subject->name}}</td>
                                    <td class="table-action">
                                        <a class="action-icon cursor-pointer" wire:click="openEditSubjectModal({{ $subject }})" title="Edit"> <i class="mdi mdi-pencil"></i></a>
                                        <a class="action-icon cursor-pointer" title="Remove" wire:click="deleteSubject({{ $subject }})"> <i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">Not exist any subjects</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix mt-2">
                        <div class="float-left" style="margin: 0;">
                            <p>Total <strong style="color: red">{{ $subjects->total() }}</strong></p>
                        </div>
                        <div class="float-right" style="margin: 0;">
                            {{ $subjects->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject modal -->
    @if ($showSubjectModal)
    <div id="subject-modal" class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{ $editSubjectId ? "Edit Subject" : "Add Subject"}}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetValues"></button>
                </div>
                <div class="modal-body">
                    <form action="#" class="">
                        <div class="row">
                            <div class="col-md-6">
                                <x-form-input wire:model="name" name="name" label="Name" placeholder="Name" type="text" />
                                <x-form-select wire:model="state" :items="$states" name="state" label="State" />
                            </div>
                            <div class="col-md-6">
                                <x-form-select-multi wire:model="selectedGrades" :items="$grades" name="selectedGrades" label="Grade" />
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <button class="btn btn-primary" id="save_subject" type="button" wire:click="saveSubject">Submit</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>


@section('script')
<script>
    function resetSubjects() {
        console.log('reset subjects')
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure?',
            text: "All subjects will be removed permanently!",
            showCancelButton: true,
            confirmButtonText: 'Remove',
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('resetSubjects');
            }
        })
    }
</script>
@endsection