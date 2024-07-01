<div>
    @php
    $title = "Feedback";
    $breadcrumbs = ["Community", "Feedback"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm"   x-on:click="function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'TOTM Email',
                                text: 'Are you sure?',
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    @this.call('sendTotmEmail');
                                }
                            })}">Send TOTM email</button>
                    </div>
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
                            <a href="#totm" data-bs-toggle="tab" aria-expanded="false" class="nav-link {{$active_status == 'totm' ? 'active' : ''}}" wire:click="changeStatus('totm')">
                                <span class="d-md-block">TOTM</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#rejected" data-bs-toggle="tab" aria-expanded="false" class="nav-link {{$active_status == 'rejected' ? 'active' : ''}}" wire:click="changeStatus('rejected')">
                                <span class="d-md-block">Rejected</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <livewire:admin.community.feedback-table :status="$active_status" :key="$active_status" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>