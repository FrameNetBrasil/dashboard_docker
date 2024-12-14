<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthenticateException;
use Illuminate\Http\Request;
use Orkester\Security\MAuth;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->authenticate($request);
        return $next($request);
    }
    protected function authenticate($request)
    {
        if (MAuth::isLogged()) {
            return true;
        }
        $this->unauthenticated($request);
    }

    protected function unauthenticated($request)
    {
        throw new AuthenticateException('User is not authenticated. Please, login to access this page.', 401);
    }
}
