<div>
    @php
    $title = "End of holiday student list(Not Scheduled)";
    $breadcrumbs = ["End of holiday", "Student list(Not scheduled)"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <span class="form-label fw-bold">Send reminder email &nbsp;</span>
                        <div>
                            <input type="checkbox" id="send_reminder_email" name="send_reminder_email" data-switch="success" {{ !empty($reminder_email_status_option->option_value) ? 'checked' : ''}} wire:click="changeReminderEmailStatus()">
                            <label for="send_reminder_email" data-on-label="Yes" data-off-label="No"></label>
                        </div>
                    </div>
                    <livewire:admin.end-of-holiday.new-year-student-table type="not-scheduled" />
                </div>
            </div>
        </div>
    </div>
</div>