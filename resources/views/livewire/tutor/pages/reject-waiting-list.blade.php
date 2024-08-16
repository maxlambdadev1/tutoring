<div >
    @section('title')
    Tutoring for student // Alchemy Tuition
    @endsection
    @section('description')
    @endsection

    <div class="" style="height:100vh;">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-4 mt-md-5 text-center">
                    <h2 class="mb-4">Thank you</h2>
                    <p>We have removed you from our priority waiting list.</p>
                    <p>If we can offer any support to {{$child->first_name ?? ''}} in the future, please don't hesitate to get in touch.</p>
                </div>
            </div>
        </div>
    </div>
</div>
