<?php

namespace App\Models;

use App\Services\AppService;
use Carbon\Carbon;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;

class UserModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('user');
        self::attribute('idUser', key: Key::PRIMARY);
        self::attribute('login');
        self::attribute('passMD5');
        self::attribute('config');
        self::attribute('name');
        self::attribute('email');
        self::attribute('status');
        self::attribute('auth0IdUser');
        self::attribute('auth0CreatedAt');
        self::attribute('lastLogin', type: Type::TIMESTAMP);
        self::attribute('idLanguage', type: Type::INTEGER);
        self::associationMany('groups', model: GroupModel::class, associativeTable: 'user_group');
    }

    public static function createUser(array $data): void {
        $idLanguage = AppService::getCurrentIdLanguage();
        self::insert([
            'login' => $data['email'],
            'email' => $data['email'],
            'name' => $data['name'] ?? '',
            'auth0IdUser' => $data['auth0IdUser'] ?? '',
            'auth0CreatedAt' => $data['auth0CreatedAt'] ?? '',
            'idLanguage' => $idLanguage,
            'passMD5' => md5('default'),
            'lastLogin' => Carbon::now(),
            'active' => 0,
            'status' => 1,
            'config' => "O:8:\"stdClass\":1:{s:14:\"fnbrIdLanguage\";i:{$idLanguage};}"
        ]);
    }

    public static function updateLastLogin(int $idUser): void
    {
        self::update([
            'idUser' => $idUser,
            'lastLogin' => Carbon::now(),
        ]);
    }
}

