<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class UDFeatureModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('udfeature');
        self::attribute('idUDFeature', key: Key::PRIMARY);
        self::attribute('udFeature');
        self::attribute('info');
        self::attribute('type', reference: 'typeinstance.typeInstance');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('typeinstance', model: TypeInstanceModel::class);
    }

}
