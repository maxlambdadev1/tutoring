<div>
    @php
    $title = "Edit Prices";
    $breadcrumbs = ["Payments", "Edit prices"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row" x-data="{isSearching : @entangle('is_searching'), showDropdown : false}">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="search_str">
                                    Select stuedent and tutor
                                    <div class="spinner-grow spinner-grow-sm text-primary" role="status" x-show="isSearching == true"></div>
                                </label>
                                <div class="input-group" id="dropdown-toggle" data-bs-toggle="dropdown">
                                    <input wire:model="search_str" id="search_str" name="search_str" x-on:keydown.enter="isSearching = true" wire:keydown.enter="searchStudentAndTutors1" x-on:focus="showDropdown = true;" x-on:blur="setTimeout(() => showDropdown = false, 200)" type="text" class="form-control" placeholder="Type here to find the student and tutor...">
                                </div>
                                <ul class="dropdown-menu dropdown-menu-md p-2" :class="{'d-block show' : showDropdown}">
                                    @forelse ($searched_sessions as $session)
                                    <li class="cursor-pointer" wire:click="selectSession({{ $session->id }})" x-on:click="showDropdown = false">
                                        <a class="dropdown-item">
                                            <div><span class='fw-bold'>{{$session->child->child_name ?? '-'}} </span>(Parent: {{ $session->parent->parent_email ?? '-'}})</div>
                                            <span>Tutor: {{ $session->tutor->tutor_email ?? '-' }}</span>
                                        </a>
                                    </li>
                                    @empty
                                    <li class="cursor-pointer">
                                        <a class="dropdown-item">
                                            <span>There are no results</span>
                                        </a>
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!empty($prices))
            <div class="card">
                <div class="card-body">
                    <x-session-alert />
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Parent price</div>
                        <div class="col-md-8">${{$prices['parent_price']}}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Parent discount</div>
                        <div class="col-md-8">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-price_parent_discount">$</span>
                                <input type="text" class="form-control form-control-sm" name="price_parent_discount" wire:model="price_parent_discount" aria-label="price_parent_discount" aria-describedby="basic-price_parent_discount" />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Tutor price</div>
                        <div class="col-md-8">${{$prices['tutor_price']}}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Tutor offer</div>
                        <div class="col-md-8">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-price_tutor_offer">$</span>
                                <input type="text" class="form-control form-control-sm" name="price_tutor_offer" wire:model="price_tutor_offer" aria-label="price_tutor_offer" aria-describedby="basic-price_tutor_offer" />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Tutor increase</div>
                        <div class="col-md-8">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-price_tutor_increase">*7%</span>
                                <input type="text" class="form-control form-control-sm"  name="price_tutor_increase" wire:model="price_tutor_increase" aria-label="price_tutor_increase" aria-describedby="basic-price_tutor_increase" />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Total session price</div>
                        <div class="col-md-8">${{$prices['final_parent_price']}}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Total tutor price</div>
                        <div class="col-md-8">${{$prices['final_tutor_price']}}</div>
                    </div>
                    <div class="d-flex mt-3">
                        <span class="form-label fw-bold">Sync progress reports with the increased amount? &nbsp;</span>
                        <div>
                            <input type="checkbox" id="tutor-increase-sync-input" name="tutor-increase-sync-input" wire:model="tutor_increase_sync_input" data-switch="success">
                            <label for="tutor-increase-sync-input" data-on-label="Yes" data-off-label="No"></label>
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        This will duplicate an existing progress report to match the increase amount.
                    </div>
                    <div class="row">
                        <div class="col-md-7 text-center py-3">
                            <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm" wire:click="updatePrice">Update</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>