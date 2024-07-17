<ul class="side-nav">

    <li class="side-nav-item">
        <a href="{{route('tutor.dashboard')}}" class="side-nav-link" wire:navigate>
            <i class="uil uil-home-alt"></i>
            <span> Home </span>
        </a>
    </li>
    
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarJobs" aria-expanded="false" aria-controls="sidebarJobs" class="side-nav-link">
            <i class="uil  uil-map-pin-alt"></i>
            <span> Jobs </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarJobs">
            <ul class="side-nav-second-level">
                <li>
                    <a href="{{ route('tutor.jobs.all-jobs') }}" wire:navigate>All jobs</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sessions" aria-expanded="false" aria-controls="sidebarThirdparty" class="side-nav-link">
            <i class="uil  uil-calendar-alt"></i>
            <span> Sessions </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sessions">
            <ul class="side-nav-second-level">
                <li>
                    <a href="{{ route('tutor.sessions.previous-sessions') }}" wire:navigate>Previous sessions</a>
                </li>
                <li>
                    <a href="{{ route('tutor.sessions.unconfirmed-sessions') }}" wire:navigate>Unconfirmed sessions</a>
                </li>
                <li>
                    <a href="{{ route('tutor.sessions.scheduled-sessions') }}" wire:navigate>Upcoming sessions</a>
                </li>
                <li>
                    <a href="{{ route('tutor.sessions.add-session') }}" wire:navigate>Add session</a>
                </li>
            </ul>
        </div>
    </li>
    
    <li class="side-nav-item">
        <a href="{{route('tutor.your-students')}}" class="side-nav-link" wire:navigate>
            <i class="uil  uil-smile-beam"></i>
            <span> Your students </span>
        </a>
    </li>
    <li class="side-nav-item">
        <a href="{{route('tutor.payments')}}" class="side-nav-link" wire:navigate>
            <i class="uil  uil-dollar-alt"></i>
            <span> Payments </span>
        </a>
    </li>

    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#your-detail" aria-expanded="false" aria-controls="sidebarYourDetail" class="side-nav-link">
            <i class="uil  uil-user"></i>
            <span> Your detail </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="your-detail">
            <ul class="side-nav-second-level">
                <li>
                    <a href="{{ route('tutor.your-detail.update-detail') }}" wire:navigate>Personal Info</a>
                </li>
                <li>
                    <a href="{{ route('tutor.your-detail.update-subjects') }}" wire:navigate>Subjects</a>
                </li>
                <li>
                    <a href="{{ route('tutor.your-detail.update-availabilities') }}" wire:navigate>Availabilities</a>
                </li>
                <li>
                    <a href="{{ route('tutor.your-detail.update-payment') }}" wire:navigate>Payment</a>
                </li>
                <li>
                    <a href="{{ route('tutor.your-detail.update-wwcc') }}" wire:navigate>WWCC</a>
                </li>
            </ul>
        </div>
    </li>
</ul>