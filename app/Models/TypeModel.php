<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class TypeModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('type');
        self::attribute('idType', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
    }

}
