<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class POSModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('pos');
        self::attribute('idPOS', key: Key::PRIMARY);
        self::attribute('POS');
        self::attribute('entry');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::associationOne('entity', model: EntityModel::class);
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
    }

}