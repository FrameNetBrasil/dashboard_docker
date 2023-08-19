<?php

namespace App\Http\Controllers;

use App\Models\LanguageModel;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function main()
    {
        session(['currentController' => "Reinventa"]);
        $this->data->frame2 = DashboardService::frame2();
        ddump($this->data->frame2);


        return $this->render("dashboard/main");
    }

    public function language(string $lang) {
        $language = (object)LanguageModel::one(['language', '=', $lang], ['idLanguage', 'language']);
        session(['currentLanguage' => $language]);
        return redirect("/");
    }
}

