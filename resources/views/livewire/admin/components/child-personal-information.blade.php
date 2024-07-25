<div>
    <div class="">
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a href="#child-personal-information-detail{{$child->id}}" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                    <span class="">Detail</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#child-personal-information-comment{{$child->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                    <span class="">Comment</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane show active" id="child-personal-information-detail{{$child->id}}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <p class="col-md-4 fw-bold">Child ID</p>
                            <p class="col-md-8">{{$child->id}}</p>
                        </div>
                        <div class="row">
                            <p class="col-md-4 fw-bold">Student name</p>
                            <p class="col-md-8">{{$child->child_name ?? '-'}}</p>
                        </div>
                        <div class="row">
                            <p class="col-md-4 fw-bold">Studen year</p>
                            <p class="col-md-8">{{$child->child_year ?? '-'}}</p>
                        </div>
                        <div class="row">
                            <p class="col-md-4 fw-bold">Student school</p>
                            <p class="col-md-8">{{$child->child_school ?? '-'}}</p>
                        </div>
                        <div class="row">
                            <p class="col-md-4 fw-bold">Student is active</p>
                            <p class="col-md-8">{{!!$child->child_status ? 'Active' : 'Inactive'}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="child-personal-information-comment{{$child->id}}">
                <div class="row">
                    <div class="col-6">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="child-comment{{$child->id}}" class="form-label">Comment</label>
                                    <textarea class="form-control" x-ref="child_comment{{$child->id}}" id="child-comment{{$child->id}}" rows="5"></textarea>
                                </div>
                                <input type="button" wire:click="addComment({{$child->id}}, $refs.child_comment{{$child->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-6 history-detail">
                        @forelse ($child->history as $item)
                        <div class="mb-1">
                            <div>{{ $item->comment}}</div>
                            <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
                        </div>
                        @empty
                        There are no any comments for this child yet.
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr />
    <div class="">
        <h3 class="fw-bold">Parent Information</h3>
        <livewire:admin.components.parent-personal-information parent_id="{{$child->parent->id}}" key="{{$child->id}}-{{$child->parent->id}}" />
    </div>
</div>