<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Model;
use Orkester\Persistence\Repository;

class StaticAnnotationMMModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('staticannotationmm')
            ->attribute('idStaticAnnotationMM', key: Key::PRIMARY)
            ->attribute('idFrameElement', type: Type::INTEGER)
            ->attribute('idLU', type: Type::INTEGER)
            ->attribute('idLemma', type: Type::INTEGER)
            ->attribute('idFrame', type: Type::INTEGER)
            ->attribute('idStaticObjectSentenceMM', type: Type::INTEGER, key: Key::FOREIGN)
            ->associationOne('frameElement', model: FrameElementModel::class, key: 'idFrameElement')
            ->associationOne('staticObjectSentenceMM', model: StaticObjectSentenceMMModel::class, key: 'idStaticObjectSentenceMM')
            ->associationOne('lu', model: LUModel::class, key: 'idLU')
            ->associationOne('lemma', model: LemmaModel::class, key: 'idLemma')
            ->associationOne('frame', model: FrameModel::class, key: 'idFrame');
    }

}
