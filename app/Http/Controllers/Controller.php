<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Http\HtmxResponse;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Orkester\Manager;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected object $data;
    protected string $notify;

    public function __construct(
        protected readonly HtmxRequest $request
    )
    {
        $this->data = Manager::getData();
        $this->data->currentUrl = $request->getCurrentUrl() ?? '/' . $request->path();
        $this->notify = '';
    }

    public function render(string $view)
    {
        $response = response()
            ->view($view, ['data' => $this->data]);
        if ($this->notify != '') {
            $response->header('HX-Trigger', $this->notify);
        }
        return $response;
    }

    public function clientRedirect(string $url) {
        return new HtmxResponseClientRedirect($url);
    }

    public function notify($type, $message)
    {
//        HX-Trigger: {"showMessage":"Here Is A Message"}
        $this->notify = json_encode([
            'notify' => [
                'type' => $type,
                'message' => $message
            ]
        ]);
    }

}
