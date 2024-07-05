<div>
    @php
    $title = "Jobs";
    $breadcrumbs = ["Jobs", "All jobs"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <h4>All Job Opportunities:</h4>
                        <p>Here you will find all students currently seeking a tutor.</p>
                        <p>You can toggle between <b>Instant Accept face-to-face</b>, <b>Instant Accept online</b> and <b>Waiting List</b> opportunities below.
                            Please ensure you read the details of each opportunity carefully and only accept students you are confident you can commit to long term; the long term relationship is where the magic happens!</p>
                        <p class="mb-0">For all <b>Instant Accept</b> jobs the parent has provided their available times and days, and if you can match these then you can accept the student instantly.
                            For <b>Waiting List</b> opportunities, you provide the days and times that you can do and we offer them to the parent, allowing 48 hours to respond.</p>
                    </div>
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <a href="#instance-f2f" data-bs-toggle="tab" aria-expanded="false" class="nav-link {{$type == 'instance-f2f' ? 'active bg-light' : ''}}" wire:click="changeType('instance-f2f')">
                                <span class="d-md-block">Instance Accept Face-To-Face</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#instance-online" data-bs-toggle="tab" aria-expanded="true" class="nav-link {{$type == 'instance-online' ? 'active bg-light' : ''}}" wire:click="changeType('instance-online')">
                                <span class="d-md-block">Instance Accept Online</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#waiting-list" data-bs-toggle="tab" aria-expanded="false" class="nav-link {{$type == 'waiting-list' ? 'active bg-light' : ''}}" wire:click="changeType('waiting-list')">
                                <span class="d-md-block">Waiting List</span>
                            </a>
                        </li>
                    </ul>
                    @if ($type == 'instance-f2f')
                    @json($jobs)
                    @elseif ($type == 'instance-online')
                    @json($jobs)
                    @elseif ($type == 'waiting-list')
                    @json($jobs)
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>