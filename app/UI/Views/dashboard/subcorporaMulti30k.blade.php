<div class="dashboard-subtitle">{{__('dashboard.imageAnnotation')}}</div>
<div class="flex flex-row gap-x-2">
    <div class="card w-96 dashboard-card1">
        <div class="header">{{__('dashboard.annotatedImages')}}</div>
        <div class="body">
            {{$data->audition['sentences']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card2">
        <div class="header">{{__('dashboard.annotatedBBox')}}</div>
        <div class="body">
            {{$data->audition['framesText']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card3">
        <div class="header">{{__('dashboard.frames')}}</div>
        <div class="body">
            {{$data->audition['fesText']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card4">
        <div class="header">{{__('dashboard.fes')}}</div>
        <div class="body">
            {{$data->audition['lusText']}}
        </div>
    </div>
</div>
<div class="dashboard-subtitle">{{__('dashboard.ptt')}}</div>
<div class="flex flex-row gap-x-2">
    <div class="card w-96 dashboard-card2">
        <div class="header">{{__('dashboard.pttSentences')}}</div>
        <div class="body">
            {{$data->audition['bbox']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card3">
        <div class="header">{{__('dashboard.pttFrame')}}</div>
        <div class="body">
            {{$data->audition['framesBBox']}}
        </div>
    </div>
</div>
<div class="dashboard-subtitle">{{__('dashboard.pto')}}</div>
<div class="flex flex-row gap-x-2">
    <div class="card w-96 dashboard-card2">
        <div class="header">{{__('dashboard.ptoSentences')}}</div>
        <div class="body">
            {{$data->audition['bbox']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card3">
        <div class="header">{{__('dashboard.ptoFrame')}}</div>
        <div class="body">
            {{$data->audition['framesBBox']}}
        </div>
    </div>
</div>
<div class="dashboard-subtitle">{{__('dashboard.eno')}}</div>
<div class="flex flex-row gap-x-2">
    <div class="card w-96 dashboard-card2">
        <div class="header">{{__('dashboard.enoSentences')}}</div>
        <div class="body">
            {{$data->audition['bbox']}}
        </div>
    </div>
    <div class="card w-96 dashboard-card3">
        <div class="header">{{__('dashboard.enoFrame')}}</div>
        <div class="body">
            {{$data->audition['framesBBox']}}
        </div>
    </div>
</div>