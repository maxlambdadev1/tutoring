<div>
    @php
    $title = "Template Settings";
    $breadcrumbs = ["Owner", "Setting", "Templates"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="option_name" class="form-label">Select option</label>
                                <select name="option_name" id="option_name" class="form-select " wire:model="option_name" wire:change="selectOption">
                                    <option value="">Please select...</option>
                                    <option value="training1">Training page 1</option>
                                    <option value="training2">Training page 2</option>
                                    <option value="training3">Training page 3</option>
                                    <option value="training4">Training page 4</option>
                                    <option value="training5">Training page 5</option>
                                    <option value="training6">Training page 6</option>
                                    <option value="verify-email">Verify email page</option>
                                    <option value="help">Help template</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @if (!empty($option_value))
                    <div class="row">
                        <div class="col-md-5">
                            <textarea name="option_value" class="form-control" wire:model="option_value" rows="15">{{$option_value}}</textarea>
                            <button type="button" wire:click="saveTemplate" class="btn btn-outline-info waves-effect waves-light btn-sm mt-1">Save</button>
                        </div>
                        <div class="col-md-7">
                            <div class="border border-success border-2 rounded text-center p-3">
                                {!! $option_value !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>