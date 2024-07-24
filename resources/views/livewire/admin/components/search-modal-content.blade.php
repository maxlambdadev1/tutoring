<div>
    <input type="search" class="form-control" id="search_text" wire:model="search_text" placeholder="Search..." wire:keydown.enter="searchAllMembers">
    <div class="mt-2">
        @if (!empty($tutor_results) || !empty($parent_results) || !empty($child_results))
        @foreach ($tutor_results as $tutor)
        <div class="pb-1 px-2">
            <a href="{{route('admin.search', ['focus' => 'tutor', 'id' => $tutor->id])}}" wire:navigate>
                <b>{{$tutor->tutor_name}}</b><span class="text-black"> ({{$tutor->tutor_email}}) in tutors({{$tutor->sessions}} sessions)</span>
            </a>
        </div>
        @endforeach
        @foreach ($parent_results as $parent)
        <div class="pb-1 px-2">
            <a href="{{route('admin.search', ['focus' => 'parent', 'id' => $parent->id])}}" wire:navigate>
                <b>{{$parent->parent_first_name . ' ' . $parent->parent_last_name}}</b><span class="text-black"> ({{$parent->parent_email}}) in parents({{$parent->sessions}} sessions)</span>
            </a>
        </div>
        @endforeach
        @foreach ($child_results as $child)
        <div class="pb-1 px-2">
            <a href="{{route('admin.search', ['focus' => 'child', 'id' => $child->id])}}" wire:navigate>
                <b>{{$child->child_name}}</b><span class="text-black"> (parent: {{$child->parent->parent_email}}) in children({{$child->sessions}} sessions)</span>
            </a>
        </div>
        @endforeach
        @else
        <p class="text-center mt-5">There are no results.</p>
        @endif
    </div>