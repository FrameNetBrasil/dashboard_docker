<?php

namespace App\Http\Middleware;

use App\Services\AppService;
use Closure;
use Illuminate\Http\Request;
use Orkester\Security\MAuth;
use Orkester\Manager;
use Symfony\Component\HttpFoundation\Response;

class Session
{
    public function handle(Request $request, Closure $next): Response
    {
//        debug('==========================================');
//        debug('=================== in session middleware');
//        debug('==========================================');
//        $data = Manager::getData();
        MAuth::init();
//        $data->isAdmin = MAuth::checkAccess('ADMIN');
//        $data->isMaster = MAuth::checkAccess('MASTER');
//        $data->isAnno = MAuth::checkAccess('ANNO');
        // language
        $language = session('currentLanguage') ?? null;
        $idLanguage = $language ? $language->idLanguage : '';
        if ($idLanguage == '') {
            $idLanguage = config('webtool.defaultIdLanguage') ?? '';
            if ($idLanguage == '') {
                $idLanguage = 1;
            }
            AppService::setCurrentLanguage((int)$idLanguage);
        }
//        $data->idLanguage = (int)$idLanguage;
        return $next($request);
    }
}
