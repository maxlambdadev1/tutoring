<div class="container mx-0">
    <div class="row">
        <div class="col-6">
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Organisation name:</div>
                <div class="col-md-6">{{ $row->organisation_name }}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Primary contact first name:</div>
                <div class="col-md-6">{{ $row->primary_contact_first_name }}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Primary contact last name:</div>
                <div class="col-md-6">{{ $row->primary_contact_last_name }}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Primary contact role:</div>
                <div class="col-md-6">{{ $row->primary_contact_role }}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Primary contact phone number:</div>
                <div class="col-md-6">{{ $row->primary_contact_phone}}</div>
            </div>
        </div>
        <div class="col-6">
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Primary contact email:</div>
                <div class="col-md-6">{{ $row->primary_contact_email}}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Email for billing:</div>
                <div class="col-md-6">{{ $row->email_for_billing ?? '-' }}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Email for confirmations:</div>
                <div class="col-md-6">{{ $row->email_for_confirmations ?? '-' }}</div>
            </div>
            <div class="row pb-2">
                <div class="col-md-6 fw-bold">Comment:</div>
                <div class="col-md-6">{{ $row->comment ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <a href="{{route('admin.thirdparty.edit', $row->id)}}" wire:navigate class="action-icon" title="Edit"> <i class="mdi mdi-pencil"></i></a>
    </div>
</div>