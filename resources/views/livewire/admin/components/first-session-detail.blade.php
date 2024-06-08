<div class="container mx-0">
    <x-session-usual-description :session="$row" />
    <livewire:admin.components.first-session-action-detail ses_id="{{$row->id}}" :key="$row->id" />
</div>