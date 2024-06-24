<div>
    @php
    $title = "End of holiday tutor list";
    $breadcrumbs = ["End of holiday", "Tutor list"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body" x-data="{isTutorSurveyCronClicked  : false, isTutorSurveySmsFollowupClicked: false, isTutorSurveyEmailFollowupClicked: false, isTutorSurveySmsFollowupClicked1: false, isTutorDeactivateCronClicked:false, isEmptyHolidayDataClicked: false }">
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm my-1" 
                        :disabled="isTutorSurveyCronClicked" x-on:click="isTutorSurveyCronClicked = true"
                        wire:click="tutorSurveyCron">Survey cron</button>
                    <button type="button" class="btn btn-outline-secondary waves-effect btn-sm my-1" 
                        :disabled="isTutorSurveySmsFollowupClicked" x-on:click="isTutorSurveySmsFollowupClicked = true"
                        wire:click="tutorSurveySmsFollowup">SMS follow up</button>
                    <button type="button" class="btn btn-outline-success waves-effect waves-light btn-sm my-1" 
                        :disabled="isTutorSurveyEmailFollowupClicked"   x-on:click="isTutorSurveyEmailFollowupClicked = true"
                        wire:click="tutorSurveyEmailFollowup">Email follow up</button>
                    <button type="button" class="btn btn-outline-info waves-effect waves-light btn-sm my-1" 
                        :disabled="isTutorSurveySmsFollowupClicked1"   x-on:click="isTutorSurveySmsFollowupClicked1 = true"
                        wire:click="tutorSurveySmsFollowup(true)">Final SMS follow up</button>
                    <button type="button" class="btn btn-outline-warning waves-effect waves-light btn-sm my-1" 
                        :disabled="isTutorDeactivateCronClicked" x-on:click="function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Are you sure?',
                                text: 'All tutors that have not responded will be deactivated. Continue?',
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    isTutorDeactivateCronClicked = true;
                                    @this.call('tutorDeactivateCron');
                                }
                            })}"
                        >Deactivate tutor</button>
                    <button type="button" class="btn btn-outline-dark waves-effect waves-light btn-sm my-1" 
                        :disabled="isEmptyHolidayDataClicked"   x-on:click="function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Are you sure?',
                                text: 'All data will be deleted. Continue?',
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    isEmptyHolidayDataClicked = true;
                                    @this.call('emptyHolidayData');
                                }
                            })}"
                        >Empty all data</button>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <livewire:admin.end-of-holiday.new-year-tutor-table />
                </div>
            </div>
        </div>
    </div>
</div>
