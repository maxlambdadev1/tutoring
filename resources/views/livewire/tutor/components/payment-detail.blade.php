<div class="row">
    <div class="col-md-12">
        <p class="mb-0"><b>Lesson date and time: &nbsp;</b>{{$row->session_date}}</p>
        <p class="mb-0"><b>Status: &nbsp;</b>{{$row->session_charge_status}}</p>
        <p class="mb-0"><b>Lesson length: &nbsp;</b>{{$row->session_length}}</p>
        <p class="mb-0"><b>Confirmed on: &nbsp;</b>{{$row->session_last_changed}}</p>
        <p class="mb-0"><b>Payment amount: &nbsp;</b>{{$row->session_tutor_price}}</p>
        <p class="mb-0"><b>Date and time paid: &nbsp;</b>{{$row->session_charge_time}}</p>
        <p class="mb-0"><b>View invoice: &nbsp;</b>{{$row->pdf}}</p>
    </div>
</div>