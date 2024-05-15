<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Model;
use Orkester\Persistence\Repository;

class StaticBBoxMMModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('staticbboxmm')
            ->attribute('idStaticBBoxMM', key: Key::PRIMARY)
            ->attribute('x', type: Type::INTEGER)
            ->attribute('y', type: Type::INTEGER)
            ->attribute('width', type: Type::INTEGER)
            ->attribute('height', type: Type::INTEGER)
            ->attribute('idStaticObjectMM', type: Type::INTEGER, key: Key::FOREIGN)
            ->associationOne('staticObjectMM', model: StaticObjectMMModel::class, key: 'idObjectMM');
    }

}
