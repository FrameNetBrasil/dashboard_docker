<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class TransactionModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('transaction');
        self::attribute('idTransaction', key: Key::PRIMARY);
        self::attribute('name');
        self::attribute('description');
        self::associationMany('access', model: AccessModel::class, keys: 'idTransaction');
    }

}

