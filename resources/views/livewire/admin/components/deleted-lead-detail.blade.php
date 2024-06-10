<div class="container mx-0">
    <div class="row mt-3">
        <div class="col-12">
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a href="#action{{$row->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                        <span class="d-md-block">Actions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#reschedule{{$row->id}}" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                        <span class="d-md-block">Reschedule suggestions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#rejection{{$row->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        <span class="d-md-block">Rejection comments</span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="action{{$row->id}}">
                    <div class="row">
                        <div class="col-6">
                            <div class="row mb-2">
                                <div class="col-12">
                                    <div class="mb-2">
                                        <label for="comment" class="form-label">Comment</label>
                                        <textarea class="form-control" x-ref="comment{{$row->id}}" id="comment" rows="5"></textarea>
                                    </div>
                                    <input type="button" wire:click="addComment({{$row->id}}, $refs.comment{{$row->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-6 history-detail">
                            @forelse ($row->history as $item)
                            <div class="mb-1">
                                <div>{{ $item->comment}}</div>
                                <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
                            </div>
                            @empty
                            There are no any comments for this lead yet.
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="tab-pane history-detail" id="reschedule{{$row->id}}">
                    @forelse ($row->reschedule_details as $item)
                    <div class="mb-1">
                        <div>{{ $item->tutor->tutor_name}} suggested new date and time on {{ str_replace('PM', 'PM, ', str_replace('AM', 'AM, ',$item->date)) }}</div>
                        <span class="text-muted"><small>{{ $item->tutor->tutor_name }} on {{ $item->last_updated }}</small></span>
                    </div>
                    @empty
                    There are no any comments for this lead yet.
                    @endforelse
                </div>
                <div class="tab-pane history-detail" id="rejection{{$row->id}}">
                    @forelse ($row->reject_history as $item)
                    <div class="mb-1">
                        <div>{{ $item->comment}}</div>
                        <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
                    </div>
                    @empty
                    There are no any comments for this lead yet.
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>