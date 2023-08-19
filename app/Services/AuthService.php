<?php

namespace App\Services;

use App\Models\UserModel;
use App\Resources\UserResource;
use Illuminate\Support\Arr;
use Orkester\Manager;
use Auth0\SDK\Auth0;

class AuthService
{
    public function auth0Login($userInfo)
    {
        $userData = [
            'auth0IdUser' => $userInfo['user_id'],
            'email' => $userInfo['email'],
            'auth0CreatedAt' => $userInfo['created_at'],
            'name' => $userInfo['name'],
            'nick' => $userInfo['nickname']
        ];
        $userResource = new UserResource();
        $user = $userResource->one([
            ['email','=', $userData['email']]
        ]);
        if (!isset($user['login'])) {
            UserModel::createUser($userData);
            $result = 'new';
        } else {
            if ($user['status'] == '0') {
                $result = 'pending';
            } else {
                $groups = $userResource->getGroups($user['idUser']);
                $user['groups'] = collect($groups)
                    ->pluck('name')
                    ->all();
                session(['user' => $user]);
                ddump($user);
                UserModel::updateLastLogin($user['idUser']);
                ddump("[LOGIN] Authenticated {$user['login']}");
                $result = 'logged';
            }
        }
        return $result;
    }
    public function getAuth0Data(): array
    {
        return [
            'domain' => config('webtool.login.AUTH0_DOMAIN'),
            'clientId' => config('webtool.login.AUTH0_CLIENT_ID'),
            'clientSecret' => config('webtool.login.AUTH0_CLIENT_SECRET'),
            'cookieSecret' => config('webtool.login.AUTH0_COOKIE_SECRET'),
            'redirect_uri' => config('webtool.login.AUTH0_CALLBACK_URL'),
            'tokenAlgorithm' => 'HS256'
        ];
    }

    public function getAuth0(): Auth0
    {
        $auth0Data = $this->getAuth0Data();
        return new Auth0($auth0Data);
    }

    public function auth0LoginUrl() {
        $data = $this->getAuth0Data();
        return $this->getAuth0()->login($data['redirect_uri']);
    }

    public function checkAccess(string $transaction): bool
    {
        $user = $this->getUser();
        $result = false;
        if (isset($user['groups'])) {
            if ((in_array('ADMIN', $user['groups']))
                || (in_array($transaction, $user['groups']))) {
                $result = true;
            }
        }
        return $result;
    }

    public function isLogged(): bool
    {
        return !is_null(session('user') ?? null);
    }

    public function getUser(): array|null
    {
        return session('user');
    }

    public function logout(): void
    {
        session(['user' => null]);
    }

}
