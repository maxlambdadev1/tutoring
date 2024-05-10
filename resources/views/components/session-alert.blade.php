@if (session('info'))
    <div class="alert alert-success" role="alert">
        <strong>Success - </strong> {{session('info')}}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger" role="alert">
        <strong>Error - </strong> {{session('error')}}
    </div>
@endif