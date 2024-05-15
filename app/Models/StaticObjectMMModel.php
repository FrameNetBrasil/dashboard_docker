<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Model;
use Orkester\Persistence\Repository;

class StaticObjectMMModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('staticobjectmm')
            ->attribute('idStaticObjectMM', key: Key::PRIMARY)
            ->attribute('scene')
            ->attribute('nobdnbox')
            ->attribute('idFlickr30kEntitiesChain', type: Type::INTEGER)
            ->associationMany('staticBBoxMM', model: StaticBBoxMMModel::class, keys: 'idStaticObjectMM');
    }
}
