<?php

namespace Orkester\Security;

use App\Repositories\User;

class MAuth
{
    private static ?object $login = null;

    public static function init(): void
    {
        self::$login = session('user') ?? null;
    }

    public static function isLogged(): bool
    {
        return !is_null(self::$login);
    }

    public static function getLogin(): ?object
    {
        return self::$login;
    }

    public static function checkAccess(string $group): bool
    {
        if ($group == '') {
            return true;
        } else {
            $result = false;
            if (!is_null(self::$login)) {
                $user = self::$login;
                $result = User::isMemberOf($user,$group) || User::isManager($user);
            }
            return $result;
        }
    }
    public static function logout(): void
    {
        session(['user' => null]);
        self::$login = null;
    }
}
