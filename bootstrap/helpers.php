<?php

use Illuminate\Support\Facades\Log;

if (! function_exists('ddump')) {
    function ddump(mixed ...$var) {
        if (env('LOG_TRACE_PORT') != '0') {
            foreach ($var ?? [] as $m) {
                $message = print_r($m, true);
                Log::channel('trace')->debug($message);
            }
        }
    }
}
