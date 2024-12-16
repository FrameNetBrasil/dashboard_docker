<?php

use App\Services\DashboardService;
use Illuminate\Support\Facades\Artisan;

Artisan::command('dashboard_update', function () {
    $frame2 = DashboardService::frame2();
    $frame2PPM = DashboardService::frame2PPM();
    $frame2NLG = DashboardService::frame2NLG();
    $frame2Gesture = DashboardService::frame2Gesture();
    $audition = DashboardService::audition();
    $audition['origin'] = DashboardService::auditionOrigin();
    $multi30k = DashboardService::multi30k();
    $multi30kEntity = DashboardService::multi30kEntity();
    $multi30kEvent = DashboardService::multi30kEvent();
    $data = (object)[
        'frame2' => $frame2,
        'frame2PPM' => $frame2PPM,
        'frame2NLG' => $frame2NLG,
        'frame2Gesture' => $frame2Gesture,
        'audition' => $audition,
        'multi30k' => $multi30k,
        'multi30kEntity' => $multi30kEntity,
        'multi30kEvent' => $multi30kEvent,
    ];
    DashboardService::updateTable($data);
})->purpose('');
