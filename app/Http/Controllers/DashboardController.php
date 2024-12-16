<?php

namespace App\Http\Controllers;

use App\Database\Criteria;
use App\Services\AppService;
use App\Services\DashboardService;
use App\Services\GTService;
use App\Services\McGovernService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

#[Middleware(name: 'web')]
class DashboardController extends Controller
{
    #[Get(path: '/')]
    public function main()
    {
        $lang = AppService::getCurrentLanguageCode();
        App::setLocale($lang);
        session(['currentController' => "Reinventa"]);
        //if (DashboardService::mustCalculate()) {
        //if (true) {
//            $frame2 = DashboardService::frame2();
//            $frame2PPM = DashboardService::frame2PPM();
//            $frame2NLG = DashboardService::frame2NLG();
//            $frame2Gesture = DashboardService::frame2Gesture();
//            $audition = DashboardService::audition();
//            $audition['origin'] = DashboardService::auditionOrigin();
//            $multi30k = DashboardService::multi30k();
//            $multi30kEntity = DashboardService::multi30kEntity();
//            $multi30kEvent = DashboardService::multi30kEvent();
//            $data = (object)[
//                'frame2' => $frame2,
//                'frame2PPM' => $frame2PPM,
//                'frame2NLG' => $frame2NLG,
//                'frame2Gesture' => $frame2Gesture,
//                'audition' => $audition,
//                'multi30k' => $multi30k,
//                'multi30kEntity' => $multi30kEntity,
//                'multi30kEvent' => $multi30kEvent,
//            ];
//            DashboardService::updateTable($data);
//        } else {
            $data = (object)[];
            DashboardService::getFromTable($data);
            $audition = $data->audition;
            $frame2 = $data->frame2;
            $frame2PPM = $data->frame2PPM;
            $frame2NLG = $data->frame2NLG;
            $frame2Gesture = $data->frame2Gesture;
            $multi30k = $data->multi30k;
            $multi30kEntity = $data->multi30kEntity;
            $multi30kEvent = $data->multi30kEvent;
            $audition['origin'] = DashboardService::auditionOrigin();
            $multi30k['chart'] = DashboardService::multi30kChart();
//        }
        return view('Dashboard.main',[
            'frame2' => $frame2,
            'frame2PPM' => $frame2PPM,
            'frame2NLG' => $frame2NLG,
            'frame2Gesture' => $frame2Gesture,
            'audition' => $audition,
            'multi30k' => $multi30k,
            'multi30kEntity' => $multi30kEntity,
            'multi30kEvent' => $multi30kEvent,
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

