<div class="container mx-0">
    <div class="row">
        <div class="col-3">
            <x-session-usual-description :session="$row" />
            <livewire:admin.components.first-session-action-detail ses_id="{{$row->id}}" type="daily" :key="$row->id" />
        </div>
    </div>
</div>