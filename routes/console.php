<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('schema', function () {
    debug(collect(Schema::getColumns('user'))->pluck('name')->all());
});

/*
 * Multimodal
 */

Artisan::command('webtool40_multimodal_video_process {videoPath}', function ($videoPath) {
    $service = new \App\Offline\Multimodal\videoPreprocess($videoPath);
    $service->process();
})->purpose('');

Artisan::command('webtool40_multimodal_audio_process {videoPath}', function ($videoPath) {
    $service = new \App\Offline\Multimodal\audioPreprocess($videoPath);
    $service->process();
})->purpose('');

//Artisan::command('webtool40_multimodal_video_process_doc {idDocument}', function ($idDocument) {
//    $service = new \App\Offline\Multimodal\videoPreprocess_Kenneth($idDocument);
//    $service->process();
//})->purpose('');
//
//Artisan::command('webtool40_multimodal_audio_process_doc {idDocument}', function ($idDocument) {
//    $service = new \App\Offline\Multimodal\audioPreprocess_The_crush($idDocument);
//    $service->process();
//})->purpose('');

Artisan::command('webtool40_multimodal_video_process_doc {idDocument}', function ($idDocument) {
    $service = new \App\Offline\Multimodal\videoPreprocess_Lais($idDocument);
    $service->process();
})->purpose('');

Artisan::command('webtool40_multimodal_audio_process_doc {idDocument}', function ($idDocument) {
    $service = new \App\Offline\Multimodal\audioPreprocess_document($idDocument);
    $service->process();
})->purpose('');
