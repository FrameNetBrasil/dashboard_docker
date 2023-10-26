<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class UDRelationModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('udrelation');
        self::attribute('idUDRelation', key: Key::PRIMARY);
        self::attribute('info');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idTypeInstance', key: Key::FOREIGN);
        self::attribute('name', reference: 'entity.entries.name');
        self::attribute('description', reference: 'entity.entries.description');
        self::attribute('idLanguage', reference: 'entity.entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('typeInstance', model: TypeInstanceModel::class);
    }

}
