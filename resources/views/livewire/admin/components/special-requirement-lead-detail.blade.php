<div class="container mx-0">
    <div class="row">
        <div class="col-4">
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Tutor name</div>
                <div class="col-md-6">{{$row->tutor->tutor_name ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Tutor email</div>
                <div class="col-md-6">{{$row->tutor->user->email ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Tutor phone</div>
                <div class="col-md-6">{{$row->tutor->tutor_phone ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Parent's requirement</div>
                <div class="col-md-6">{{$row->special_request_content ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Tutor's response</div>
                <div class="col-md-6">{{$row->special_request_response ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Postcode</div>
                <div class="col-md-6">{{$row->parent->parent_postcode}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Source</div>
                <div class="col-md-6">{{$row->source ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Prefered gender</div>
                <div class="col-md-6">{{$row->preferred_gender ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Previously associated tutors</div>
                <div class="col-md-6">{{'-'}}</div>
            </div>
            @php
            $views = 0;
            $visited_tutors_array = [];
            if (!empty($row->visited_tutors)) {
            foreach ($row->visited_tutors as $visited_tutor) {
            $views += $visited_tutor->cnt;
            array_push($visited_tutors_array, $visited_tutor->tutor->tutor_name . "(" . $visited_tutor->cnt . ")");
            }
            }
            $visited_tutors = implode(', ', $visited_tutors_array);
            @endphp
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Views</div>
                <div class="col-md-6">{{$views}} times</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Viewed by</div>
                <div class="col-md-6">{{$visited_tutors}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-12">
                    <input type="button" value="Approve" wire:click="approveSpecialRequirementResponse({{$row->id}})" class="btn btn-outline-primary waves-effect waves-light btn-sm">
                    <input type="button" value="Reject" wire:click="rejectSpecialRequirementResponse({{$row->id}})" class="btn btn-outline-danger waves-effect waves-light btn-sm">
                </div>
            </div>
        </div>
        <div class="col-7">
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">What is the main result you are looking for?</div>
                <div class="col-md-6">{{$row->main_result ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">How would you describe their current performance at school?</div>
                <div class="col-md-6">{{$row->performance ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">How would you describe their attitude towards school?</div>
                <div class="col-md-6">{{$row->attitude ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">How would you describe their mind?</div>
                <div class="col-md-6">{{$row->mind ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">How would you describe their personality?</div>
                <div class="col-md-6">{{$row->personality ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">What are their 3 favourite things to do?</div>
                <div class="col-md-6">{{$row->favourite ?? '-'}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Notes</div>
                <div class="col-md-6">{!! $row->job_notes ? nl2br($row->job_notes) : '-' !!}</div>
            </div>
        </div>
    </div>
</div>