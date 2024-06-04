<div class="container-fluid">
    <div class="row">
        <div class="col-5">
            <h3 class="fw-bold">Student details</h3>
            <div class="row">
                <div class="col-md-6 fw-bold">Student name:</div>
                <div class="col-md-6">{{ $row->child->child_name }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Student school:</div>
                <div class="col-md-6">{{ $row->child->child_school }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Student grade:</div>
                <div class="col-md-6">{{ $row->child->child_year }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">&nbsp;</div>
            </div>
            <h3 class="fw-bold">Tutor details</h3>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor email:</div>
                <div class="col-md-6">{{ $row->tutor->user->email }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor phone:</div>
                <div class="col-md-6">{{ $row->tutor->tutor_phone }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor address:</div>
                <div class="col-md-6">{{ $row->tutor->address }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor postcode:</div>
                <div class="col-md-6">{{ $row->tutor->postcode }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Tutor Stripe ID:</div>
                <div class="col-md-6">{{ $row->tutor->tutor_stripe_user_id ?? '-' }}</div>
            </div>
        </div>
        <div class="col-7">
            <h3 class="fw-bold">Parent details</h3>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent email:</div>
                <div class="col-md-6">{{ $row->parent->parent_email }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent phone:</div>
                <div class="col-md-6">{{ $row->parent->parent_phone }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent address:</div>
                <div class="col-md-6">{{ $row->parent->parent_address }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent postcode:</div>
                <div class="col-md-6">{{ $row->parent->parent_postcode }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Parent Stripe ID:</div>
                <div class="col-md-6">{{ $row->parent->stripe_customer_id }}</div>
            </div>
            <h3 class="fw-bold">Session details</h3>
            <div class="row">
                <div class="col-md-6 fw-bold">Session length:</div>
                <div class="col-md-6">{{ $row->session_length ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Confirmed on:</div>
                <div class="col-md-6">{{ !empty($row->session_length) && $row->session_length > 0 && $row->session_last_changed ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Session reason:</div>
                <div class="col-md-6">{{ $row->session_reason ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Paid on:</div>
                <div class="col-md-6">{{ $row->session_charge_time ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-6 fw-bold">Session feedback:</div>
                <div class="col-md-6">{{ $row->session_feedback ?? '-' }}</div>
            </div>
        </div>
    </div>
    <livewire:admin.components.session-action-detail ses_id="{{$row->id}}" :key="$row->id" />
</div>