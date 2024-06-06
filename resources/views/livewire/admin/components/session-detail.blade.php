<div class="container-fluid">
    <x-session-usual-description :session="$row" />
    <livewire:admin.components.session-action-detail ses_id="{{$row->id}}" :key="$row->id" />
</div>