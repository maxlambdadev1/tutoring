<div>
    @php
    $title = "Current Students";
    $breadcrumbs = ["Current Student"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mb-3">Current Students</h4>
                    @forelse ($students as $student)
                    <div class="bg-light p-2 mb-3">
                        <h5 class="fw-bold mb-3">{{$student->child_name}}</h5>
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a href="#student-detail{{$student->id}}" data-bs-toggle="tab" aria-expanded="true" class="nav-link active px-2 px-md-3">
                                    <span class="">Detail</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#student-goal{{$student->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link px-2 px-md-3">
                                    <span class="">Goals</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#student-cancellation-fee{{$student->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link px-2 px-md-3">
                                    <span class="">Cancellation fee</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="student-detail{{$student->id}}">
                                <p>Year {{$student->child_year}} {{$student->first_session->session_subject ?? ''}}</p>
                                <p><b>Address:</b> {{$student->parent->parent_address ?? ''}} {{$student->parent->parent_suburb ?? ''}}, {{$student->parent->parent_postcode ?? ''}}</p>
                                <span><b>Parent name:</b> {{$student->parent->parent_name ?? ''}}</span>
                                <p><b>Phone:</b> {{$student->parent->parent_phone ?? ''}}</p>

                                <span><b>Date of first session:</b> {{$student->first_session->session_date ?? ''}}</span>
                                <p class="mb-0"><b>Next scheduled session:</b> {{$student->next_session->session_date ?? ''}}</p>
                                <p class="mb-0"><b>Completed sessions:</b> {{$student->total_sessions ?? ''}}</p>
                                <p><b>Sessions until next pay increase:</b> {{$student->next_increase ?? ''}}</p>

                                <span><b>Current rate of pay: $</b>{{$student->price ?? ''}} per hour</span>
                            </div>
                            <div class="tab-pane" id="student-goal{{$student->id}}">
                                <p class="fw-bold">What are the main area of focus for the student?</p>
                                <p>{{$student->goals['q1'] ?? ''}}</p>
                                <hr />
                                <p class="fw-bold">What is the main goal to work towards over the next 10 weeks?</p>
                                <p>{{$student->goals['q2'] ?? ''}}</p>
                                <hr />
                                <p class="fw-bold">How will you achieve this goal?</p>
                                <p>{{$student->goals['q3'] ?? ''}}</p>
                                <hr />
                                <p class="fw-bold">What positive attributes does the student have?</p>
                                <p>{{$student->goals['q4'] ?? ''}}</p>
                            </div>
                            <div class="tab-pane" id="student-cancellation-fee{{$student->id}}">
                                <div class="row">
                                    <div class="col-md-6 my-2">
                                        <p>Our cancellation policy requires that a parent notifies you of any session cancellation at least 12 hours prior to a scheduled session or a cancellation fee of 50% of the usual lesson charge will apply.</p>
                                        <p>We do leave this up to your discretion and believe that every situation should be carefully considered; a student being sick is very different from a student forgetting they had something else on. An unjustified cancellation fee can damage a relationship so we do want you to use wisdom when choosing to charge it.</p>
                                        <p>We do also suggest that if it is the first time they have cancelled within 12 hours, you provide them with a warning that it will apply the next time.</p>
                                        <p>With all this being said, if you would like to charge a cancellation fee please click the button below. It will then be reviewed by our team and if we agree with your call, we will process it for you and you will receive payment within 24 hours.</p>
                                    </div>
                                    @if (!empty($student->last_session))
                                    <div class="col-md-6 my-2">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Last session</label>
                                            <select name="type" id="type" class="form-select ">
                                                <option value="{{$student->last_session->id}}">{{$student->last_session->session_date}} {{$student->last_session->session_time_ampm}}</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cancellation_fee_reason{{$student->id}}" class="form-label">Reason</label>
                                            <textarea class="form-control " x-ref="cancellation_fee_reason{{$student->id}}" id="cancellation_fee_reason{{$student->id}}" rows="5"></textarea>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                wire:click="submitCancellationFee({{$student->last_session->id}}, $refs.cancellation_fee_reason{{$student->id}}.value)">Charge</button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="fw-bold">There are no current students</div>
                    @endforelse
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mb-3">Previous Students</h4>
                    <div class="row">
                        @forelse ($prev_students as $student)
                        <div class="col-md-6">
                            <div class="bg-light p-2 mb-3">
                            {{$student->child_name ?? '-'}}
                            </div>
                        </div>
                        @empty
                        <div class="col-12 fw-bold">There are no previous students</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>