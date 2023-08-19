<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class EntryModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('entry');
        self::attribute('idEntry', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('name');
        self::attribute('description');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::associationOne('language', model: LanguageModel::class);
        self::associationOne('entity', model: EntityModel::class, key: 'idEntity');
    }

}