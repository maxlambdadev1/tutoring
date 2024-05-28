<ul class="side-nav">

    <li class="side-nav-item">
        <a href="{{route('admin.dashboard')}}" class="side-nav-link" wire:navigate>
            <i class="uil uil-home-alt"></i>
            <span> Home </span>
        </a>
    </li>

    @can('action-owner')
        <li class="side-nav-title side-nav-item">Owner</li>

        <li class="side-nav-item">
            <a href="/users" wire:navigate class="side-nav-link">
                <i class="mdi mdi-crowd"></i>
                <span> Team members </span>
            </a>
        </li>

        <li class="side-nav-item">
            <a href="/users/create" wire:navigate class="side-nav-link">
                <i class="uil uil-user-plus"></i>
                <span> Add member </span>
            </a>
        </li>

        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarSetting" aria-expanded="false" aria-controls="sidebarSetting" class="side-nav-link">
                <i class="uil uil-bright"></i>
                <span> Setting </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarSetting">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.setting.states-grades') }}" wire:navigate>State & Grade</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.setting.subjects') }}" wire:navigate>Subjects</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.setting.session-types') }}" wire:navigate>Session types</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.setting.availabilities') }}" wire:navigate>Availabilities</a>
                    </li>
                    
                </ul>
            </div>
        </li>
    @endcan

    @can('action-manager')
        <li class="side-nav-title side-nav-item">Manager</li>

        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarLeads" aria-expanded="false" aria-controls="sidebarLeads" class="side-nav-link">
                <i class="uil uil-map-pin-alt"></i>
                <span> Leads </span>
                <span class="badge bg-danger text-white ms-3 mt-0">New</span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarLeads">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.leads.all-leads') }}" wire:navigate>All Leads</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.leads.leads-screening') }}" wire:navigate>Leads screening</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.leads.new-leads') }}" wire:navigate>New Leads</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.leads.active-leads') }}" wire:navigate>Active Leads</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.leads.focus-leads') }}" wire:navigate>Focus Leads</a>
                    </li>
                    <li>
                        <a href="#">Current Leads</a>
                    </li>
                    <li>
                        <a href="#">Deleted Leads</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.leads.create') }}" wire:navigate>Add Leads</a>
                    </li>
                    <li>
                        <a href="#">Lead Map</a>
                    </li>
                    <li>
                        <a href="#">Welcome Calls</a>
                    </li>
                    <li>
                        <a href="#">Find Tutor</a>
                    </li>
                    <li>
                        <a href="#">Replacement Tutors</a>
                    </li>
                </ul>
            </div>
        </li>
    @endcan

    <li class="side-nav-title side-nav-item">Collaborator</li>
    
</ul>