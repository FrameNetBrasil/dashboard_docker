<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class UDPOSModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('udpos');
        self::attribute('idUDPOS', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entity.entries.name');
        self::attribute('description', reference: 'entity.entries.description');
        self::attribute('idLanguage', reference: 'entity.entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
    }

}
