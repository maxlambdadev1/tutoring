<div x-data="tutor_profile_init" style="background-image: url({{$background_image}}); background-size: cover;">
    @section('title')
    Alchemy | Tutor profile
    @endsection
    @section('description')
    @endsection

    <div class="">
        <div class="container py-3 py-md-4">
            <div class="row text-center">
                <div class="col-12">
                    <div class="bg-warning py-3 py-md-4 mb-3 mb-md-4">
                        <img src="{{asset($tutor->getPhoto())}}" class="border border-5 border-white rounded-circle mb-3 mb-md-4" width="200" />
                        <h3 class="text-white">{{$tutor->tutor_name}}</h3>
                    </div>
                    <div class="card mb-3 mb-md-4">
                        <div class="card-body">
                            <h2 class="mb-3">Education</h2>
                            <p class="fw-3 mb-3">{{$tutor->question1 ?? ''}}</p>
                            <h2 class="mb-3">Experience</h2>
                            <p class="fw-3 mb-3">{{$tutor->question2 ?? ''}}</p>
                            <h2 class="mb-3">Goals</h2>
                            <p class="fw-3 mb-3">{{$tutor->question3 ?? ''}}</p>
                            <h2 class="mb-3">Personality</h2>
                            <p class="fw-3">{{$tutor->question4 ?? ''}}</p>
                        </div>
                    </div>
                    <div class="card mb-3 mb-md-4">
                        <div class="card-body bg-warning">
                            <h2 class="text-white fst-italic mb-3">Rating</h2>
                            <img src="/images/stars.png" alt="rating" class="mb-2">
                        </div>
                    </div>
                    <div class="card mb-3 mb-md-4">
                        <div class="card-body">
                            <h2 class="mb-3">What other parents have said:</h2>
                            <div id="tutor_review" class="carousel slide" data-bs-ride="carousel" style="min-height: 200px;">
                                @if (count($reviews) > 0)
                                <div class="carousel-indicators">
                                    @foreach ($reviews as $review)
                                    <button type="button" data-bs-target="#tutor_review" class="bg-black {{ $loop->first ? 'active' : '' }}" data-bs-slide-to="{{$loop->index}}"></button>
                                    @endforeach
                                </div>
                                <div class="carousel-inner">
                                    @foreach ($reviews as $review)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <p class="fw-3">{{$review->rating_comment}}</p>
                                        <h3 class="text-warning">{{$review->parent->parent_first_name ?? ''}}, {{ !empty($review->parent->parent_suburb) ?$review->parent->parent_suburb : $review->parent->parent_state }}</h3>
                                    </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#tutor_review" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-black rounded-circle p-2"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#tutor_review" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-black rounded-circle p-2"></span>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 mb-md-4">
                        <div class="card-body">
                            <h2 class="mb-3">All Alchemy tutors:</h2>
                            <p class="fw-3">Are hand selected and meet our strict criteria.</p>
                            <p class="fw-3">Hold working with children checks that are manually verified.</p>
                            <p class="fw-3">Pass our comprehensive training and accreditation program.</p>
                            <p class="fw-3">Are monitored regularly to ensure the highest quality.</p>
                            <p class="fw-3">Are selected for more than just academic abilities; they are role models and motivators.</p>
                        </div>
                    </div>
                    <div class="">
                        <a href="/">
                            <img src="/images/logo.png" height="100" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tutor_profile_init', () => ({}))
    })
</script>