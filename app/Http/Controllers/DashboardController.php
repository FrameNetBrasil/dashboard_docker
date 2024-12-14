<?php

namespace App\Http\Controllers;

use App\Database\Criteria;
use App\Services\AppService;
use App\Services\DashboardService;
use App\Services\GTService;
use App\Services\McGovernService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;

#[Middleware(name: 'web')]
class DashboardController extends Controller
{
    #[Get(path: '/')]
    public function main()
    {
        session(['currentController' => "Reinventa"]);
//        if (DashboardService::mustCalculate()) {
            $frame2 = DashboardService::frame2();
//            $audition = DashboardService::audition();
//            $multi30k = DashboardService::multi30k();
//            DashboardService::updateTable($this->data);
//        } else {
//            DashboardService::getFromTable($this->data);
//            $multi30k['chart'] = DashboardService::multi30kChart();
//        }
        return view('Dashboard.main',[
            'frame2' => $frame2
        ]);
    }

    #[Get(path: '/changeLanguage/{language}')]
    public function changeLanguage(Request $request, string $language)
    {
        $currentURL = $request->header("Hx-Current-Url");
        $data = Criteria::byFilter("language", ['language', '=', $language])->first();
        AppService::setCurrentLanguage($data->idLanguage);
        return $this->redirect($currentURL);
    }

    #[Get(path: '/mcgovern')]
    public function mcgovern()
    {
        session(['currentController' => "McGovern"]);
        $this->data->mcgovern = McGovernService::dashboard();
        return $this->render("dashboard/mcgovern");
    }

    #[Get(path: '/gt')]
    public function gt()
    {
        session(['currentController' => "GT"]);
        $this->data->gt = GTService::dashboard();
        ddump($this->data->gt);
        return $this->render("dashboard/gt");
    }
}

