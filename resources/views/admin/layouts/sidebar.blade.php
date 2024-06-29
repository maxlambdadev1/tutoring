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
                        <a href="{{ route('admin.leads.deleted-leads') }}" wire:navigate>Deleted Leads</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.leads.waiting-list') }}" wire:navigate>Waiting list</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.leads.create') }}" wire:navigate>Add Leads</a>
                    </li>
                    <li>
                        <a href="#">Lead Map</a>
                    </li>
                    <li>
                        <a href="#">Find Tutor</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.leads.replacement-tutor') }}" wire:navigate>Replacement tutor</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.leads.special-requirement-approval') }}" wire:navigate>Special Requirement Approval</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarThirdparty" aria-expanded="false" aria-controls="sidebarThirdparty" class="side-nav-link">
                <i class="uil uil-object-group"></i>
                <span> Third party </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarThirdparty">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.thirdparty.create') }}" wire:navigate>Add organisation</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.thirdparty.create-lead') }}" wire:navigate>Add thirdparty lead</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.thirdparty.sessions') }}" wire:navigate>Thirdparty sessions</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.thirdparty.cancellation-fee') }}" wire:navigate>Cancellation fee</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.thirdparty.organisations') }}" wire:navigate>Organisation list</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#creativekids" aria-expanded="false" aria-controls="sidebarThirdparty" class="side-nav-link">
                <i class="uil uil-kid"></i>
                <span> Creative kids </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="creativekids">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.creativekids.add-creative-leads') }}" wire:navigate>Add booking </a>
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
                        <a href="{{ route('admin.sessions.all-sessions') }}" wire:navigate>All sessions</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.unconfirmed-sessions') }}" wire:navigate>Unconfirmed sessions</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.scheduled-sessions') }}" wire:navigate>Upcoming sessions</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.no-scheduled-sessions') }}" wire:navigate>No scheduled sessions</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.no-session-2-weeks') }}" wire:navigate>No session in 2 weeks</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.first-sessions') }}" wire:navigate>First sessions</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.daily-first-sessions') }}" wire:navigate>Daily first sessions</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.not-continuing-sessions') }}" wire:navigate>Not continuing sessions</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.progress-report') }}" wire:navigate>Progress report</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.add-session') }}" wire:navigate>Add session</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.cancellation-fee') }}" wire:navigate>Cancellation fee</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.rescheduled-sessions') }}" wire:navigate>Rescheduled sessions</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarTutors" aria-expanded="false" aria-controls="sidebarTutors" class="side-nav-link">
                <i class="uil uil-user-check"></i>
                <span> Tutors </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarTutors">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.tutors.current-tutors') }}" wire:navigate>Current tutors</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutors.new-tutors') }}" wire:navigate>New tutors</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutors.past-tutors') }}" wire:navigate>Past tutors</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutors.tutor-application') }}" wire:navigate>Tutor application</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutors.tutor-application-stats') }}" wire:navigate>Tutor application stats</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutors.offers-volume') }}" wire:navigate>Dormant tutors</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutors.tutor-check-in') }}" wire:navigate>Tutor check in</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutors.tutor-first-session') }}" wire:navigate>Tutor first session</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutors.set-online-room') }}" wire:navigate>Set class room</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutors.have-references') }}" wire:navigate>Have references</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tutors.recruiter') }}" wire:navigate>Recruiters</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#wwcc" aria-expanded="false" aria-controls="sidebarWwcc" class="side-nav-link">
                <i class="uil uil-shield-exclamation"></i>
                <span> WWCC </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="wwcc">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.wwcc.verify-wwcc') }}" wire:navigate>Verify WWCC </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.wwcc.chasing-wwcc') }}" wire:navigate>Chasing WWCC </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.wwcc.audit-wwcc') }}" wire:navigate>Audit WWCC </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#students" aria-expanded="false" aria-controls="sidebarStudents" class="side-nav-link">
                <i class="uil uil-smile-beam"></i>
                <span> Students </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="students">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.students.current-students') }}" wire:navigate>Current Students</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.students.past-students') }}" wire:navigate>Past Students</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.students.add-students') }}" wire:navigate>Add Students</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#parents" aria-expanded="false" aria-controls="sidebarParents" class="side-nav-link">
                <i class="uil  uil-users-alt"></i>
                <span> Parents </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="parents">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.parents.current-parents') }}" wire:navigate>Parent list</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.parents.parents-payment-details') }}" wire:navigate>Parent payment list</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.parents.manual-payers') }}" wire:navigate>Manual payers</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.parents.parent-check-in') }}" wire:navigate>Parent check in</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#payments" aria-expanded="false" aria-controls="sidebarPayments" class="side-nav-link">
                <i class="uil  uil-dollar-alt"></i>
                <span> Payments </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="payments">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.payments.price-audit') }}" wire:navigate>Price audit</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.payments.edit-prices') }}" wire:navigate>Edit prices</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.payments.failed-payments') }}" wire:navigate>Failed payments</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.payments.manual-payments') }}" wire:navigate>Manual payments</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.payments.margins') }}" wire:navigate>Margins</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#end-of-holiday" aria-expanded="false" aria-controls="sidebarEndofholiday" class="side-nav-link">
                <i class="uil   uil-sunset"></i>
                <span> End of holiday </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="end-of-holiday">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.end-of-holiday.new-year-tutor') }}" wire:navigate>Tutor list</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.end-of-holiday.new-year-student') }}" wire:navigate>Student - not continuing</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.end-of-holiday.new-year-student-not-scheduled') }}" wire:navigate>Student - no lesson scheduled</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.end-of-holiday.replacement') }}" wire:navigate>Replacement</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="sidebarReports" class="side-nav-link">
                <i class="uil  uil-document"></i>
                <span> Reports </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="reports">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ route('admin.reports.daily-report') }}" wire:navigate>Daily report</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.conversion-report') }}" wire:navigate>Conversion report</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.all-sessions') }}" wire:navigate>All sessions</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.team-goals') }}" wire:navigate>Team goals</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.all-sessions-google') }}" wire:navigate>Google Ads Booking Sessins</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.monthly-report') }}" wire:navigate>Monthly report</a>
                    </li>
                </ul>
            </div>
        </li>
    @endcan

    <li class="side-nav-title side-nav-item">Collaborator</li>
    
</ul>