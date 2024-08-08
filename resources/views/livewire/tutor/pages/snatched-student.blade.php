<div>
    @section('title')
    Student opportunity
    @endsection
    @section('description')
    @endsection

    <div class="text-center" style="height:100vh;" x-data="snatched_student_init">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <h3>OH NO!</h3>
                    <h1>THIS STUDENT JUST GOT SNATCHED UP BY ANOTHER TUTOR!</h1>
                    <h3 class="mb-3">HOW ABOUT ONE OF THESE...</h3>
                    <div class="mb-5 col-md-6 mx-auto">
                    @forelse($jobs as $job_item)
                    <div class="mb-2 bg-warning text-white fw-bold p-1">
                        <p class="mb-0">GRADE: {{$job_item->child->child_year}}</p>
                        <p class="mb-0">SUBJECT: {{$job_item->subject}}</p>
                        @if ($job->session_type_id == 1)
                        <p class="mb-0">LOCATION: {{$job_item->location}}</p>
                        <a href="/student-opportunity?url={{base64_encode('job_id='.$job->id . '&tutor_id=' . $tutor->id)}}" class="text-black">VIEW MORE</a>
                        @else
                        <p class="mb-0">Online Student <a href="/student-opportunity?url={{base64_encode('job_id='.$job->id . '&tutor_id=' . $tutor->id)}}" class="text-black">VIEW MORE</a></p>
                        @endif
                    </div>
                    @empty
                    <p class="my-5">There are no jobs for you.</p>
                    @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid position-fixed bottom-0 d-flex justify-content-center align-items-center px-0">
            <button type="button" class="btn btn-dark flex-fill fs-5" x-on:click="viewAllJobs">GO TO YOUR DASHBOARD <br/>AND VIEW ALL JOBS</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('snatched_student_init', () => ({
            viewAllJobs() {
                location.href = "/all-jobs";
            }
        }))
    })
</script>