<div class="dashboard-subtitle">{{__('dashboard.imageAnnotation')}}</div>
<div class="flex flex-row gap-x-2">
    <div class="card w-96 dashboard-card1">
        <div class="header">{{__('dashboard.annotatedImages')}}</div>
        <div class="body">
            {{$data->multi30k['images']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card2">
        <div class="header">{{__('dashboard.annotatedBBox')}}</div>
        <div class="body">
            {{$data->multi30k['bbox']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card3">
        <div class="header">{{__('dashboard.frames')}}</div>
        <div class="body">
            {{$data->multi30k['framesImage']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card4">
        <div class="header">{{__('dashboard.fes')}}</div>
        <div class="body">
            {{$data->multi30k['fesImage']}}
        </div>
    </div>
</div>
<div class="dashboard-subtitle">{{__('dashboard.ptt')}}</div>
<div class="flex flex-row gap-x-2">
    <div class="card w-96 dashboard-card2">
        <div class="header">{{__('dashboard.pttSentences')}}</div>
        <div class="body">
            {{$data->multi30k['pttSentences']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card3">
        <div class="header">{{__('dashboard.pttFrame')}}</div>
        <div class="body">
            {{$data->multi30k['pttFrames']}}
        </div>
    </div>
</div>
<div class="dashboard-subtitle">{{__('dashboard.pto')}}</div>
<div class="flex flex-row gap-x-2">
    <div class="card w-96 dashboard-card2">
        <div class="header">{{__('dashboard.ptoSentences')}}</div>
        <div class="body">
            {{$data->multi30k['ptoSentences']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card3">
        <div class="header">{{__('dashboard.ptoFrame')}}</div>
        <div class="body">
            {{$data->multi30k['ptoFrames']}}
        </div>
    </div>
</div>
<div class="dashboard-subtitle">{{__('dashboard.eno')}}</div>
<div class="flex flex-row gap-x-2">
    <div class="card w-96 dashboard-card2">
        <div class="header">{{__('dashboard.enoSentences')}}</div>
        <div class="body">
            {{$data->multi30k['enoSentences']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card3">
        <div class="header">{{__('dashboard.enoFrame')}}</div>
        <div class="body">
            {{$data->multi30k['enoFrames']}}
        </div>
    </div>
</div>

<div class="dashboard-subtitle">{{__('dashboard.boxesPerMonth')}}</div>
<div class="chart-container" style="position: static; height:40vh; width:80vw">
    <canvas id="boxesPerMonth"></canvas>
</div>
</div>

@php
$labels = [];
$values = [];
foreach($data->multi30k['chart'] as $c) {
    $labels[] = $c['m'];
    $values[] = $c['value'];
}
@endphp
<script type="application/javascript">
    document.addEventListener("DOMContentLoaded", function(e) {
        (async function() {
            const ctx = document.getElementById('boxesPerMonth');
            const labels = {{ Js::from($labels) }};
            const data = {
                labels: labels,
                maintainAspectRatio: false,
                datasets: [{
                    label: '{{__('dashboard.boxesPerMonth')}}',
                    data: {{ Js::from($values) }},
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            };
            new window.Chart(ctx, {
                type: 'line',
                data: data
            });
        })();
    });
</script>