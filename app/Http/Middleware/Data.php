<?php

namespace App\Http\Middleware;

use App\Models\LanguageModel;
use App\Services\AppService;
use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Orkester\Manager;
use Symfony\Component\HttpFoundation\Response;

class Data
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        ddump('in data middleware');
        $data = $request->all();
        $language = session('currentLanguage') ?? null;
        $idLanguage = $language ? $language->idLanguage : '';
        if ($idLanguage == '') {
            $idLanguage = config('webtool.defaultIdLanguage') ?? '';
            if ($idLanguage == '') {
                $idLanguage = 1;
            }
            $language = (object)LanguageModel::one(['idLanguage', '=', $idLanguage], ['idLanguage', 'language']);
            session(['currentLanguage' => $language]);
        }
        App::setLocale($language->language);
        $data['idLanguage'] = (int)$idLanguage;
        ddump(App::currentLocale());
        $auth = new AuthService();
            session('isAdmin') ?? session(['isAdmin' => $auth->checkAccess('ADMIN') ? 'true' : 'false']);
            session('isMaster') ?? session(['isMaster' => $auth->checkAccess('MASTER') ? 'true' : 'false']);
            session('isAnno') ?? session(['isAnno' => $auth->checkAccess('ANNO') ? 'true' : 'false']);
        Manager::setData((object)$data);
        return $next($request);
    }
}
