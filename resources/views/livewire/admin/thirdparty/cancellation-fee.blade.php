<div>
    @php
    $title = "Thirdparty Cancellation fee";
    $breadcrumbs = ["Thirdparty", "Cancellation fee"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <a href="#pending" data-bs-toggle="tab" aria-expanded="false" class="nav-link {{$active_status == 'pending' ? 'active' : ''}}" wire:click="changeStatus('pending')">
                                <span class="d-md-block">Pending</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#approved" data-bs-toggle="tab" aria-expanded="true" class="nav-link {{$active_status == 'approved' ? 'active' : ''}}" wire:click="changeStatus('approved')">
                                <span class="d-md-block">Approved</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#declined" data-bs-toggle="tab" aria-expanded="false" class="nav-link {{$active_status == 'declined' ? 'active' : ''}}" wire:click="changeStatus('declined')">
                                <span class="d-md-block">Declined</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <livewire:admin.sessions.cancellation-fee-table :status="$active_status" :key="$active_status" thirdparty="true" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>