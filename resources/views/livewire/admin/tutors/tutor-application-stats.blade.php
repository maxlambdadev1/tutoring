<div>
    @php
    $title = "Tutor application stats";
    $breadcrumbs = ["Tutors", "Application stats"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h3>Total applications</h3>
                    <canvas id="total-overview"></canvas>
                    @php $total = $data['total']; @endphp
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <span class="fw-bold">Total: </span>{{$data['total']}}
                        </div>
                        @foreach($data as $key => $item)
                        @if ($key != 'total')
                        <div class="col-md-6">
                            <span class="fw-bold">{{$status[$key]}}: </span>{{$data[$key]}} ({{round(100 * $data[$key]/$total)}}%)
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h3>Accepted applications</h3>
                    <canvas id="accepted-overview"></canvas>
                    @php $total = $data['4'] + $data['5']; @endphp
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <span class="fw-bold">Total: </span>{{$total}}
                        </div>
                        <div class="col-md-6">
                            <span class="fw-bold">{{$status[4]}}: </span>{{$data[4]}} ({{round(100 * $data[4]/$total)}}%)
                        </div>
                        <div class="col-md-6">
                            <span class="fw-bold">{{$status[5]}}: </span>{{$data[5]}} ({{round(100 * $data[5]/$total)}}%)
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h3>Interviewing applications</h3>
                    <canvas id="interview-overview"></canvas>
                    @php $total = $data['2'] + $data['3']; @endphp
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <span class="fw-bold">Total: </span>{{$total}}
                        </div>
                        <div class="col-md-6">
                            <span class="fw-bold">{{$status[2]}}: </span>{{$data[2]}} ({{round(100 * $data[2]/$total)}}%)
                        </div>
                        <div class="col-md-6">
                            <span class="fw-bold">{{$status[3]}}: </span>{{$data[3]}} ({{round(100 * $data[3]/$total)}}%)
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h3>Rejected applications</h3>
                    <canvas id="rejected-overview"></canvas>
                    @php $total = $data['6'] + $data['7'] + $data['8'] + $data['9']; @endphp
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <span class="fw-bold">Total: </span>{{$total}}
                        </div>
                        <div class="col-md-6">
                            <span class="fw-bold">{{$status[6]}}: </span>{{$data[6]}} ({{round(100 * $data[6]/$total)}}%)
                        </div>
                        <div class="col-md-6">
                            <span class="fw-bold">{{$status[7]}}: </span>{{$data[7]}} ({{round(100 * $data[7]/$total)}}%)
                        </div>
                        <div class="col-md-6">
                            <span class="fw-bold">{{$status[8]}}: </span>{{$data[8]}} ({{round(100 * $data[8]/$total)}}%)
                        </div>
                        <div class="col-md-6">
                            <span class="fw-bold">{{$status[9]}}: </span>{{$data[9]}} ({{round(100 * $data[9]/$total)}}%)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var colors = {
        '1': '#003f5c',
        '2': '#2f4b7c',
        '3': '#665191',
        '4': '#a05195',
        '5': '#d45087',
        '6': '#f95d2a',
        '7': '#ff7c43',
        '8': '#ffa600',
        '9': '#f95d6a',
    };
    var doughnutOptions = {
        segmentShowStroke: true,
        segmentStrokeColor: "#fff",
        segmentStrokeWidth: 2,
        percentageInnerCutout: 45, // This is 0 for Pie charts
        animationSteps: 100,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
    };

    let data = @json($data); //{1:23, 2:523, ..., 'total' : 3212}
    let status = @json($status); //{"1": "Applied as tutor", "2": "Scheduling interview",../. ,  "9": "Closed"}

    pieChart('total-overview', [...Object.values(status)], [data[1], data[2], data[3], data[4], data[5], data[6], data[7], data[8], data[9]], [...Object.values(colors)]);
    pieChart('accepted-overview', [status[4], status[5]], [data[4], data[5]], [colors[4], colors[5]]);
    pieChart('interview-overview', [status[2], status[3]], [data[2], data[3]], [colors[2], colors[3]]);
    pieChart('rejected-overview', [status[6], status[7], status[8], status[9]], [data[6], data[7], data[8], data[3]], [colors[6], colors[7], colors[8], colors[9]]);


    function pieChart(ele_id, label_arr, data_arr, color_arr) {
        let ctx = document.getElementById(ele_id);
        let chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: label_arr,
                datasets: [{
                    label: 'My First Dataset',
                    data: data_arr,
                    backgroundColor: color_arr,
                    hoverOffset: 4
                }]
            },
            options: {
                ...doughnutOptions
            }
        });
    }
</script>