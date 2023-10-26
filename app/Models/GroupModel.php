<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class GroupModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('group');
        self::attribute('idGroup', key: Key::PRIMARY);
        self::attribute('name');
        self::attribute('description');
        self::associationMany('users', model: UserModel::class, associativeTable: 'user_group');
        self::associationMany('access', model: AccessModel::class, keys: 'idGroup');
    }

}

