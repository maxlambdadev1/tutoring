<div>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a href="#parent-personal-information-detail{{$parent->id}}" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                <span class="">Detail</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#parent-personal-information-comment{{$parent->id}}" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                <span class="">Comment</span>
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane show active" id="parent-personal-information-detail{{$parent->id}}">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <p class="col-md-4 fw-bold">Parent ID</p>
                        <p class="col-md-8">{{$parent->id}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Phone</p>
                        <p class="col-md-8">{{$parent->parent_phone}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Email</p>
                        <p class="col-md-8">{{$parent->parent_email}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Address</p>
                        <p class="col-md-8">{{$parent->parent_address}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Suburb</p>
                        <p class="col-md-8">{{$parent->parent_suburb}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Postcode</p>
                        <p class="col-md-8">{{$parent->parent_postcode}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <p class="col-md-4 fw-bold">Stripe ID</p>
                        <p class="col-md-8">{{$parent->stripe_customer_id ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Referrer</p>
                        <p class="col-md-8">{{$parent->referred_id ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Heard from</p>
                        <p class="col-md-8">{{$parent->heard_from ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Parent price</p>
                        <p class="col-md-8">${{$parent->price_parent->f2f ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Parent postcode price</p>
                        <p class="col-md-8">${{$parent->price_postcode->price ?? '-'}}</p>
                    </div>
                    <div class="row">
                        <p class="col-md-4 fw-bold">Parent discount</p>
                        <p class="col-md-8">
                        {{!empty($parent->price_parent_discount->discount_amount) ? ($parent->price_parent_discount->discount_type == 'fixed' ? '$' .$parent->price_parent_discount->discount_amount : $parent->price_parent_discount->discount_amount . '%') : '-'}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="parent-personal-information-comment{{$parent->id}}">
            <div class="row">
                <div class="col-6">
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="parent-comment{{$parent->id}}" class="form-label">Comment</label>
                                <textarea class="form-control" x-ref="parent_comment{{$parent->id}}" id="parent-comment{{$parent->id}}" rows="5"></textarea>
                            </div>
                            <input type="button" wire:click="addComment({{$parent->id}}, $refs.parent_comment{{$parent->id}}.value)" value="Add comment" class="btn btn-primary btn-sm form-control">
                        </div>
                    </div>
                </div>
                <div class="col-6 history-detail">
                    @forelse ($parent->history as $item)
                    <div class="mb-1">
                        <div>{{ $item->comment}}</div>
                        <span class="text-muted"><small>{{ $item->author }} on {{ $item->date }}</small></span>
                    </div>
                    @empty
                    There are no any comments for this parent yet.
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>