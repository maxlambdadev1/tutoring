<div>
    @php
    $title = "Team members";
    $breadcrumbs = ["Owner", "Team members"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    @php
    $badges = ['bg-primary', 'bg-success', 'bg-warning'];
    $admin_badges = array();
    foreach ($admin_roles as $key => $ele) {
    $admin_badges[$ele->name] = $badges[$key];
    }
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-session-alert />
                    <div class="description text-center mb-3">
                        <a href="{{route('admin.users.create')}}" class="btn btn-primary"><i class="mdi mdi-account-plus-outline"></i> Add team member</a>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Joined Date</th>
                                    <th>Active?</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $admin)
                                <tr>
                                    <td>{{ (($admins->currentPage() - 1 ) * $admins->perPage() ) + $loop->iteration }}</td>
                                    <td>{{$admin->admin_name}}</td>
                                    <td>{{$admin->user->email}}</td>
                                    <td>{{$admin->phone ?? '-'}}</td>
                                    <td><span class="badge {{$admin_badges[$admin->admin_role->name]}} rounded-pill">{{$admin->admin_role->name}}</span></td>
                                    <td>{{$admin->created_at}}</td>
                                    <td>
                                        <!-- Switch-->
                                        @if ($admin->user_id !== auth()->user()->id)
                                        <div>
                                            <input type="checkbox" class="active_check" data-user_id="{{$admin->user_id}}" id="switch{{$admin->id}}" 
                                                @if($admin->user->active) @checked(true) @endif data-switch="success"
                                                wire:change="toggleActive({{ $admin->user }})" >
                                            <label for="switch{{$admin->id}}" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                                        </div>
                                        @else
                                        -
                                        @endif

                                    </td>
                                    <td class="table-action">
                                        <a href="{{route('admin.users.edit', $admin->id)}}" class="action-icon" title="Edit"> <i class="mdi mdi-pencil"></i></a>
                                        @if ($admin->user_id !== auth()->user()->id)
                                        <a href="javascript: void(0);" class="action-icon destroy" title="Remove" wire:click="deleteUser({{ $admin }})"> <i class="mdi mdi-delete"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix mt-2">
                        <div class="float-left" style="margin: 0;">
                            <p>Total <strong style="color: red">{{ $admins->total() }}</strong> members</p>
                        </div>
                        <div class="float-right" style="margin: 0;">
                            {{ $admins->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>