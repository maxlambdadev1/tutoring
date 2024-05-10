@props(['title', 'breadcrumbs'])

<x-slot name="header">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        @foreach($breadcrumbs as $breadcrumb)
                            @if ($loop->last)
                                <li class="breadcrumb-item active"><a href="javascript: void(0);">{{ $breadcrumb }}</a></li>
                            @else
                                <li class="breadcrumb-item">{{ $breadcrumb }}</li>
                            @endif
                        @endforeach
                    </ol>
                </div>
                <h4 class="page-title">{{ $title }}</h4>
            </div>
        </div>
    </div>
</x-slot>