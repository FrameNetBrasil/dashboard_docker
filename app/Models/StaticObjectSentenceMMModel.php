<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Model;
use Orkester\Persistence\Repository;

class StaticObjectSentenceMMModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('staticobjectsentencemm')
            ->attribute('idStaticObjectSentenceMM', key: Key::PRIMARY)
            ->attribute('name')
            ->attribute('startWord', type: Type::INTEGER)
            ->attribute('endWord', type: Type::INTEGER)
            ->attribute('idStaticSentenceMM', type: Type::INTEGER, key: Key::FOREIGN)
            ->attribute('idStaticObjectMM', type: Type::INTEGER, key: Key::FOREIGN)
            ->associationOne('staticSentenceMM', model: StaticSentenceMMModel::class, key: 'idStaticSentenceMM')
            ->associationMany('staticAnnotationMM', model: StaticAnnotationMMModel::class, keys: 'idStaticObjectSentenceMM:idStaticObjectSentenceMM')
            ->associationOne('staticObjectMM', model: StaticObjectMMModel::class, key: 'idStaticObjectMM');
    }

}
