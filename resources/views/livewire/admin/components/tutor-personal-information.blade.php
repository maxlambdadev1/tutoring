<div>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a href="#tutor-personal-information-detail{{$tutor->id}}" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                <span class="">Detail</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#tutor-personal-information-comment{{$tutor->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                <span class="">Comment</span>
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane show active" id="tutor-personal-information-detail{{$tutor->id}}">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <p class="col-md-4 fw-bold">Tutor ID</p>
                        <p class="col-md-8">{{$tutor->id}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Gender</p>
                        <p class="col-md-8">{{$tutor->gender}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Date of birth</p>
                        <p class="col-md-8">{{$tutor->birthday}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">PHone</p>
                        <p class="col-md-8">{{$tutor->tutor_phone}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Email</p>
                        <p class="col-md-8">{{$tutor->tutor_email}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Status</p>
                        <p class="col-md-8">{{!!$tutor->tutor_status ? 'active' : 'inactive'}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <p class="col-md-4 fw-bold">Address</p>
                        <p class="col-md-8">{{$tutor->address}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Suburb</p>
                        <p class="col-md-8">{{$tutor->suburb}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Postcode</p>
                        <p class="col-md-8">{{$tutor->postcode}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">State</p>
                        <p class="col-md-8">{{$tutor->state}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Onboarding status</p>
                        <p class="col-md-8">{{!!$tutor->onboarding ? 'Completed' : 'InCompleted' }}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Can accept jobs</p>
                        <p class="col-md-8">{{!!$tutor->accept_job_status ? "Can accept" : "Cann't accept"}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="tutor-personal-information-comment{{$tutor->id}}">
            <div class="row">
                <div class="col-6">
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="tutor-comment{{$tutor->id}}" class="form-label">Comment</label>
                                <textarea class="form-control" x-ref="tutor_comment{{$tutor->id}}" id="tutor-comment{{$tutor->id}}" rows="5"></textarea>
                            </div>
                            <input type="button" wire:click="addComment({{$tutor->id}}, $refs.tutor_comment{{$tutor->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                        </div>
                    </div>
                </div>
                <div class="col-6 history-detail">
                    @forelse ($tutor->history as $item)
                    <div class="mb-1">
                        <div>{{ $item->comment}}</div>
                        <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
                    </div>
                    @empty
                    There are no any comments for this tutor yet.
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>