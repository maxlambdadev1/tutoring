<div x-data="update_job_availabilities_init">
    @section('title')
    Alchemy
    @endsection
    @section('description')
    @endsection

    <div class="text-center" style="height:100vh;">
        <div class="container">
            <div class="row">
                <div x-show="!result1" class="col-12 mt-4 mt-md-5">
                    <h2 class="text-center mb-4">Tutoring for {{$child->child_name}} </h2>
                    <p class="text-center">We are still working hard on lining up a tutor for {{$child->first_name}}, but we wanted to see if you had any additional availabilities through the week that could work for lessons?</p>
                    <p class="text-center">Naturally the more availabilities we have to work with, the more tutors we have access to and the sooner we can get this lined up.</p>
                    <p class="text-center mb-4">Below are our time slots through the week and the availabilities you originally indicated. Please click on any additional times you might have to help us get this matched up ASAP!</p>
                    <form action="#" id="update_job_availabilities_form">
                        <div class="availabilities_wrapper mb-4 px-3">
                            <div class="row">
                                @php $i = 0; @endphp
                                @foreach ($total_availabilities as $item)
                                <div class="col-md text-center" data-day="{{$item->name}}">
                                    <h5>{{$item->name}}</h5>
                                    @forelse ($item->getAvailabilitiesName() as $ele)
                                    @php
                                    $avail_hour = $item->short_name . '-' . $ele;
                                    $i++;
                                    @endphp
                                    <div class="avail_hours p-0 w-100">
                                        <input type="checkbox" class="d-none" id="availability-{{$i}}" value="{{$avail_hour}}" wire:model="availabilities" x-model="availabilities" />
                                        <label for="availability-{{$i}}" class="text-center py-1 w-100">{{$ele}}</label>
                                    </div>
                                    @empty
                                    <div>Not exist</div>
                                    @endforelse
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-4 mb-4 text-center">
                            <button type="button" class="btn btn-warning text-white col-12 col-md-3 mb-2" x-on:click="updateJobAvailabilities" x-show="!loading1" :disabled="availabilities.length == 0">Submit</button>
                            <button type="button" class="btn btn-warning text-white col-12 col-md-3 mb-2" x-show="loading1" disabled>
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Submitting...</button>
                            <button type="button" class="btn btn-secondary col-12 col-md-3 mb-2" x-on:click="noUpdateJobAvailabilities" x-show="!loading2">I don’t have any other times</button>
                            <button type="button" class="btn btn-secondary col-12 col-md-3 mb-2" x-show="loading2" disabled>
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                </button>
                        </div>
                    </form>
                </div>
                <div x-show="!!result1" class="col-12 mt-4 mt-md-5 text-center">
                    <h2 class="mb-4">Thank you</h2>
                    <p>We will keep working on matching up the perfect tutor for your child.!</p>
                    <p>We will get it all sorted and confirm with you as soon as it is lined up.</p>
                    <p>If you need anything please don’t hesitate to <a href="{{env('MAIN_SITE')}}/#contact-section">get in touch!</a></p>
                </div>
                <div x-show="!!result2" class="col-12 mt-4 mt-md-5 text-center">
                    <h2 class="mb-4">Thank you</h2>
                    <p>We will continue working with the times you originally indicated.</p>
                    <p>We will continue working on this and be in touch shortly.</p>
                    <p>If you need anything please don’t hesitate to <a href="{{env('MAIN_SITE')}}/#contact-section">get in touch!</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let availabilities = @json($availabilities);

    document.addEventListener('alpine:init', () => {
        Alpine.data('update_job_availabilities_init', () => ({
            availabilities: availabilities,
            loading1: false,
            loading2: false,
            result1: false,
            result2: false,
            async updateJobAvailabilities() {
                this.loading1 = true;
                let result = await @this.call('updateJobAvailabilities');
                if (result) this.result1 = result;
                this.loading1 = false;
            },
            async noUpdateJobAvailabilities() {
                this.loading2 = true;
                let result = await @this.call('noUpdateJobAvailabilities');
                if (result) this.result2 = result;
                this.loading2 = false;
            },
        }))
    })
</script>