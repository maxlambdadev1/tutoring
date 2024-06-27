<div>
    @php
    $title = "Team Goals";
    $breadcrumbs = ["Reports", "Team goals"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" x-data="{quarter : {{$quarter}}}" x-data="{ init() {
                        } }">
                        <div class="col-6 col-md-2">
                            <div class="mb-3">
                                <label for="year" class="form-label">Year</label>
                                <input type="text" class="form-control " name="year" id="year" wire:model="year">
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="mb-3">
                                <label for="quarter" class="form-label">Quarter</label>
                                <select name="quarter" id="quarter" class="form-select " wire:model="quarter" wire:change="changeQuarter" x-on:change="quarter = $event.target.value">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="mb-3">
                                <label for="month" class="form-label">Month</label>
                                <select name="month" id="month" class="form-select " wire:model="month" wire:change="changeMonth">
                                    <option value=""></option>
                                    <option value="01" x-show="quarter == 1">January</option>
                                    <option value="02" x-show="quarter == 1">Febrary</option>
                                    <option value="03" x-show="quarter == 1">March</option>
                                    <option value="04" x-show="quarter == 2">April</option>
                                    <option value="05" x-show="quarter == 2">May</option>
                                    <option value="06" x-show="quarter == 2">June</option>
                                    <option value="07" x-show="quarter == 3">July</option>
                                    <option value="08" x-show="quarter == 3">August</option>
                                    <option value="09" x-show="quarter == 3">September</option>
                                    <option value="10" x-show="quarter == 4">October</option>
                                    <option value="11" x-show="quarter == 4">November</option>
                                    <option value="12" x-show="quarter == 4">December</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="mb-3">
                                <label for="goal_start" class="form-label">Goal($)</label>
                                <input type="text" class="form-control " name="goal_start" id="goal_start" wire:model="goal_start">
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="mb-3">
                                <label for="goal_current" class="form-label">Current Goal($)</label>
                                <input type="text" class="form-control " name="goal_current" id="goal_current" wire:model="goal_current">
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-outline-secondary waves-effect form-control" {{empty($month) ? 'disabled' : ''}} wire:click="saveGoal">Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            {{$last_updated}}
                        </div>
                        @php
                        $total_goal_current = 0;
                        $total_goal_start = 0;
                        if (!empty($goals)) {
                            foreach ($goals as $goal) {
                                if (!empty($goal)) {
                                    $total_goal_current += $goal->goal_current;
                                    $total_goal_start += $goal->goal_start;
                                }
                            }
                        }                      
                        @endphp
                        @if (!empty($total_goal_start))
                            <div class="col-md-12 mb-3">
                                @php                                    
                                    $percent = number_format(100 * ( $total_goal_current / $total_goal_start - 1), 1, '.', ''); 
                                    //$percent = 0;
                                    $width = (100 + $percent)/2;
                                    if ($width > 100) $width = 100;
                                    if ($width <= 0) $width = 1;
                                @endphp
                                <h2 class="text-end">Quarter {{$quarter}} {{$year}}</h2>
                                <div class="mb-1">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: {{ $width }}%;"  aria-valuenow="{{$percent}}" 
                                            aria-valuemin="0" aria-valuemax="100">{{$percent}}%</div>
                                    </div>
                                </div>
                                <p><b>{{$percent}}%</b> Reached</p>
                            </div>
                        @else
                            <div class="col-md-12 mb-3">
                                <h2 class="text-end">Quarter {{$quarter}} {{$year}}</h2>
                                <div class="mb-1">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 1%;" aria-valuenow="1" 
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <p><b>N/A%</b> Reached</p>
                            </div>
                        @endif
                        
                        @php $index = 0 @endphp
                        @foreach ($goals as $goal)
                            @if (!empty($goal)) 
                            <div class="col-md-12 mb-3">
                                @php                                    
                                    $percent = number_format(100 * ( $goal->goal_current / $goal->goal_start - 1), 1, '.', ''); 
                                    //$percent = 0;
                                    $width = (100 + $percent)/2;
                                    if ($width > 100) $width = 100;
                                    if ($width <= 0) $width = 1;
                                @endphp
                                <h2 class="text-end">{{$months_str_arr[$goal->month]}} {{$year}}</h2>
                                <div class="mb-1">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: {{ $width }}%;"  aria-valuenow="{{$percent}}" 
                                            aria-valuemin="0" aria-valuemax="100">{{$percent}}%</div>
                                    </div>
                                </div>
                                <p><b>{{$percent}}%</b> Reached</p>
                            </div>
                            @else
                            <div class="col-md-12 mb-3">
                                <h2 class="text-end">{{$months_str_arr[$quarter_info[$quarter][$index]] ?? ''}} {{$year}}</h2>
                                <div class="mb-1">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 1%;" aria-valuenow="1" 
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <p><b>N/A%</b> Reached</p>
                            </div>
                            @endif
                            @php $index++ @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#year').datepicker({
        autoclose: true,
        format: 'yyyy',
        viewMode: 'years',
        minViewMode: 'years',
        todayHighlight: true,
    }).on('changeDate', function(e) {
        let year = e.date.getFullYear();
        @this.call('changeYear', year);
    });
</script>