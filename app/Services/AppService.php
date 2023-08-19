<?php

namespace App\Services;

use App\Models\LanguageModel;
use App\Resources\LanguageResource;

class AppService
{
    public static function getCurrentLanguage() {
        return session('currentLanguage');
    }

    public static function setCurrentLanguage(int $idLanguage) {
        $data = (object)LanguageModel::one(['idLanguage','=', $idLanguage],['idLanguage','language']);
        session(['currentLanguage' => $data]);
    }

    public static function getCurrentIdLanguage() {
        return session('currentLanguage')->idLanguage;
    }

}
