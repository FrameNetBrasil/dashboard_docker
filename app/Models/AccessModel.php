<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class AccessModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('access');
        self::attribute('idAccess', key: Key::PRIMARY);
        self::attribute('rights', type: Type::INTEGER);
        self::attribute('idGroup', type: Type::INTEGER);
        self::attribute('idTransaction' ,type: Type::INTEGER);
        self::associationOne('group', model: GroupModel::class, key: 'idGroup');
        self::associationOne('transaction', model: TransactionModel::class, key: 'idTransaction');
    }

}

