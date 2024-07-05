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
            </ul>
        </div>
    </li>
    
</ul>