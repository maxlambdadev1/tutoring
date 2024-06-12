<div class="mb-3">
    <div class="row mt-3">
        <div class="col-12">
            <span class="fw-bold">Register url: </span>
            <span>{{$register_url}}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-5">
            <h3 class="fw-bold">School details</h3>
            <div class="row">
                <div class="col-5 fw-bold">Graduation year:</div>
                <div class="col-7">{{ $app->tutor_graduation_year}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Highschool in Australia:</div>
                <div class="col-7">{{$app->tutor_highschool_aus}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">ATAR:</div>
                <div class="col-7">{{$app->tutor_atar ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">School:</div>
                <div class="col-7">{{$app->tutor_school ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Current situation:</div>
                <div class="col-7">{{$app->tutor_current_situation ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Current university:</div>
                <div class="col-7">{{$app->tutor_current_university ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Current degree:</div>
                <div class="col-7">{{$app->tutor_current_degree ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Graduated university:</div>
                <div class="col-7">{{$app->tutor_graduated_university ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Graduated degree:</div>
                <div class="col-7">{{$app->tutor_graduated_degree ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Future university:</div>
                <div class="col-7">{{$app->tutor_future_university ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Future degree:</div>
                <div class="col-7">{{$app->tutor_future_degree ?? '-'}}</div>
            </div>
            <h3 class="fw-bold">Reference details</h3>
            <div class="row">
                <div class="col-5 fw-bold">Reference 1 First Name:</div>
                <div class="col-7">{{$app->tutor_fname1 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 1 Last Name:</div>
                <div class="col-7">{{$app->tutor_lname1 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 1 Email:</div>
                <div class="col-7">{{$app->tutor_email1 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 1 Relationship:</div>
                <div class="col-7">{{$app->tutor_relation1 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 1 status:</div>
                <div class="col-7">
                    @if (!empty($app->tutor_reference1))
                        @if ($app->tutor_reference1->reason == 'ok') 
                        <span class="badge bg-success">Yes</span>
                        @else
                        <span class="badge bg-danger">No</span> ({{$app->tutor_reference1->reason}})
                        @endif
                    @else
                    <span class="badge bg-warning">Wait</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 2 First Name:</div>
                <div class="col-7">{{$app->tutor_fname2 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 2 Last Name:</div>
                <div class="col-7">{{$app->tutor_lname2 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 2 Email:</div>
                <div class="col-7">{{$app->tutor_email2 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 2 Relationship:</div>
                <div class="col-7">{{$app->tutor_relation2 ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-5 fw-bold">Reference 2 status:</div>
                <div class="col-7">
                    @if (!empty($app->tutor_reference2))
                        @if ($app->tutor_reference2->reason == 'ok') 
                        <span class="badge bg-success">Yes</span>
                        @else
                        <span class="badge bg-danger">No</span> ({{$app->tutor_reference2->reason}})
                        @endif
                    @else
                    <span class="badge bg-warning">Wait</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-7">
            <h3 class="fw-bold">General details</h3>
            <div class="row">
                <div class="col-4 fw-bold">Referral code:</div>
                <div class="col-8">{{ $app->tutor_referral ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-4 fw-bold">Postal Code:</div>
                <div class="col-8">{{$app->postcode ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-4 fw-bold">Achievements:</div>
                <div class="col-8 history-detail">{{$app->tutor_achievements ?? '-'}}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-4 fw-bold">Introduction:</div>
                <div class="col-8 history-detail">{{$app->tutor_introduction ?? '-'}}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-4 fw-bold">Dinner:</div>
                <div class="col-8 history-detail">{{$app->tutor_dinner ?? '-'}}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-4 fw-bold">Strengths:</div>
                <div class="col-8 history-detail">{{$app->tutor_advice ?? '-'}}</div>
            </div>
            <hr />
            <div class="row">
                <div class="col-4 fw-bold">Subjects:</div>
                <div class="col-7 history-detail">{{$app->tutor_subjects ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-4 fw-bold">Tutored before:</div>
                <div class="col-8">{{$app->tutor_tutored_before ?? '-'}}</div>
            </div>
            <div class="row">
                <div class="col-4 fw-bold">Source:</div>
                <div class="col-8">{{$app->tutor_application_source ?? '-'}}</div>
            </div>
        </div>
    </div>
</div>
