<?php

namespace App\Services;

use App\Database\Criteria;
use Orkester\Security\MAuth;

class AppService
{
    static public function languagesDescription()
    {
        return Criteria::table('language')
            ->treeResult('idLanguage');
    }

    public static function getCurrentLanguage()
    {
        return session('currentLanguage');
    }

    public static function setCurrentLanguage(int $idLanguage)
    {
        $languages = self::languagesDescription();
        $data = $languages[$idLanguage][0];
        $data->idLanguage = $idLanguage;
        session(['currentLanguage' => $data]);
    }

    public static function getCurrentIdLanguage()
    {
        return session('currentLanguage')->idLanguage ?? session('idLanguage');
    }

    public static function availableLanguages()
    {
        $data = [];
        $languages = config('webtool.user')[3]['language'][3];
        foreach ($languages as $l => $language) {
            $data[] = (object)[
                'idLanguage' => $l,
                'description' => $language[0]
            ];
        }
        return $data;
    }

    static public function userLevel(): array
    {
        return Criteria::table("group")->chunkResult('idGroup', 'name');
    }

    static public function getCurrentUser(): ?object
    {
        return MAuth::getLogin();
    }

    static public function getCurrentIdUser(): ?int
    {
        $user = MAuth::getLogin();
        return $user ? $user->idUser : 0;
    }

}
