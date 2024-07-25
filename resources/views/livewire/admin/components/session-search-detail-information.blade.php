<div>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a href="#session-search-detail-information{{$session->id}}" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                <span class="">Detail</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#session-search-comment{{$session->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                <span class="">Comment</span>
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane show active" id="session-search-detail-information{{$session->id}}">
            <div class="row">
                <div class="col-md-5">
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session ID</p>
                        <p class="col-md-8">{{$session->id ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Date</p>
                        <p class="col-md-8">{{$session->session_date ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Time</p>
                        <p class="col-md-8">{{$session->session_time ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Subject</p>
                        <p class="col-md-8">{{$session->session_subject ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Price</p>
                        <p class="col-md-8">${{$session->session_price ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Tutor Price</p>
                        <p class="col-md-8">${{$session->session_tutor_price ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Penalty</p>
                        <p class="col-md-8">${{$session->session_penalty ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Overall Rating</p>
                        <p class="col-md-8">{{$session->session_overall_rating ?? '-'}}</p>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Engagement Rating</p>
                        <p class="col-md-8">{{$session->session_engagement_rating ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Understanding Rating</p>
                        <p class="col-md-8">{{$session->session_understanding_rate ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Feedback</p>
                        <p class="col-md-8">{{$session->session_feedback ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Reason</p>
                        <p class="col-md-8">{{$session->session_reason ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Invoice ID</p>
                        <p class="col-md-8">{{$session->session_invoice_id ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Bill ID</p>
                        <p class="col-md-8">{{$session->session_bill_id ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Stripe Charge ID</p>
                        <p class="col-md-8">{{$session->session_charge_id ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Stripe Charge Time</p>
                        <p class="col-md-8">{{$session->session_charge_time ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Session Stripe Transfer ID</p>
                        <p class="col-md-8">{{$session->session_transfer_id ?? '-'}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="session-search-comment{{$session->id}}">
            <div class="row">
                <div class="col-6">
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="session-comment{{$session->id}}" class="form-label">Comment</label>
                                <textarea class="form-control" x-ref="session_comment{{$session->id}}" id="session-comment{{$session->id}}" rows="5"></textarea>
                            </div>
                            <input type="button" wire:click="addComment({{$session->id}}, $refs.session_comment{{$session->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                        </div>
                    </div>
                </div>
                <div class="col-6 history-detail">
                    @forelse ($session->history as $item)
                    <div class="mb-1">
                        <div>{{ $item->comment}}</div>
                        <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
                    </div>
                    @empty
                    There are no any comments for this session yet.
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>