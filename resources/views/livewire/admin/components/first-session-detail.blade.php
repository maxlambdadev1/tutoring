<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <x-session-usual-description :session="$row" />
            <livewire:admin.components.first-session-action-detail ses_id="{{$row->id}}" :key="$row->id" />
        </div>
    </div>
</div>