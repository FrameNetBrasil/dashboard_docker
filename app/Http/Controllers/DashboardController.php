<?php

namespace App\Http\Controllers;

use App\Models\LanguageModel;
use App\Services\DashboardService;
use App\Services\GTService;
use App\Services\McGovernService;

class DashboardController extends Controller
{
    public function main()
    {
        session(['currentController' => "Reinventa"]);
        if (DashboardService::mustCalculate()) {
            $this->data->frame2 = DashboardService::frame2();
            $this->data->audition = DashboardService::audition();
            $this->data->multi30k = DashboardService::multi30k();
            DashboardService::updateTable($this->data);
        } else {
            DashboardService::getFromTable($this->data);
            $this->data->multi30k['chart'] = DashboardService::multi30kChart();
        }
        return $this->render("dashboard/main");
    }

    public function language(string $lang) {
        $language = (object)LanguageModel::one(['language', '=', $lang], ['idLanguage', 'language']);
        session(['currentLanguage' => $language]);
        return redirect("/");
    }

    public function mcgovern()
    {
        session(['currentController' => "McGovern"]);
        $this->data->mcgovern = McGovernService::dashboard();
        return $this->render("dashboard/mcgovern");
    }
    public function gt()
    {
        session(['currentController' => "GT"]);
        $this->data->gt = GTService::dashboard();
        ddump($this->data->gt);
        return $this->render("dashboard/gt");
    }
}

