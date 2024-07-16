<div>
    @php
    $title = "Update subjects";
    $breadcrumbs = ["Your detail", "Update subjects"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="container">
                        <div class="row mb-3">
                            @foreach ($all_subjects as $subject)
                            <div class="col-12 col-md-4 py-1">
                                @if (in_array($subject, $subjects))
                                <button type="button" class="btn btn-info waves-effect waves-light w-100 py-2 rounded-4" wire:click="changeSubject('{{$subject}}')">{{$subject}}</button>
                                @else
                                <button type="button" class="btn btn-outline-info waves-effect waves-light w-100 py-2 rounded-4" wire:click="changeSubject('{{$subject}}')">{{$subject}}</button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm" wire:click="updateSubjects">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>