<div>
    @php
    $title = "Thirdparty Sessions";
    $breadcrumbs = ["Thirdparty", "Sessions"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-0">
                        <label for="thirdparty_org_id" class="form-label">Select a organisation</label>
                        <select wire:model.live="thirdparty_org_id" id="thirdparty_org_id" class="form-control form-control-sm">
                            @if (!empty($organisations))
                            <option value="">Please select</option>
                            @foreach ($organisations as $org)
                            <option value="{{ $org->id }}">{{ $org->organisation_name }}</option>
                            @endforeach
                            @else
                            <option value="">There are no organisations. Please add organisations</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (!empty($thirdparty_org_id))
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.sessions.all-sessions-table :thirdparty_org_id="$thirdparty_org_id" :key="$thirdparty_org_id"/>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>