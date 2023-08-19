<?php

use Illuminate\Support\Facades\Log;

if (! function_exists('ddump')) {
    function ddump(mixed ...$var) {
        foreach ($var ?? [] as $m) {
            $message = print_r($m, true);
            Log::channel('trace')->debug($message);
        }
    }
}
