<div class="row">
    <div class="col-5">
        <h3 class="fw-bold">Student details</h3>
        <div class="row">
            <div class="col-6 fw-bold">Student name:</div>
            <div class="col-6">{{ $session->child->child_name ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Student school:</div>
            <div class="col-6">{{ $session->child->child_school ?? '-'}}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Student grade:</div>
            <div class="col-6">{{ $session->child->child_year ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">&nbsp;</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">&nbsp;</div>
        </div>
        <h3 class="fw-bold">Tutor details</h3>
        <div class="row">
            <div class="col-6 fw-bold">Tutor email:</div>
            <div class="col-6">{{ $session->tutor->tutor_email ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Tutor phone:</div>
            <div class="col-6">{{ $session->tutor->tutor_phone ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Tutor address:</div>
            <div class="col-6">{{ $session->tutor->address ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Tutor postcode:</div>
            <div class="col-6">{{ $session->tutor->postcode ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Tutor Stripe ID:</div>
            <div class="col-6">{{ $session->tutor->tutor_stripe_user_id ?? '-' }}</div>
        </div>
    </div>
    <div class="col-7">
        <h3 class="fw-bold">Parent details</h3>
        <div class="row">
            <div class="col-6 fw-bold">Parent email:</div>
            <div class="col-6">{{ $session->parent->parent_email ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Parent phone:</div>
            <div class="col-6">{{ $session->parent->parent_phone  ?? '-'}}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Parent address:</div>
            <div class="col-6">{{ $session->parent->parent_address ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Parent postcode:</div>
            <div class="col-6">{{ $session->parent->parent_postcode  ?? '-'}}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Parent Stripe ID:</div>
            <div class="col-6">{{ $session->parent->stripe_customer_id ?? '-' }}</div>
        </div>
        <h3 class="fw-bold">Session details</h3>
        <div class="row">
            <div class="col-6 fw-bold">Session length:</div>
            <div class="col-6">{{ $session->session_length ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Confirmed on:</div>
            <div class="col-6">{{ !empty($session->session_length) && $session->session_length > 0 && $session->session_last_changed ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Session reason:</div>
            <div class="col-6">{{ $session->session_reason ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Paid on:</div>
            <div class="col-6">{{ $session->session_charge_time ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="col-6 fw-bold">Session feedback:</div>
            <div class="col-6">{{ $session->session_feedback ?? '-' }}</div>
        </div>
    </div>
</div>