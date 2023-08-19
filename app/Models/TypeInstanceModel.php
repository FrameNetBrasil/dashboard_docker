<?php

namespace App\Models;

use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class TypeInstanceModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('typeinstance');
        self::attribute('idTypeInstance', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('info');
        self::attribute('flag');
        self::attribute('idType', key: Key::FOREIGN);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idColor', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('color', model: ColorModel::class, key: 'idColor');
        self::associationOne('type', model: TypeModel::class, key: 'idType');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
    }

}
