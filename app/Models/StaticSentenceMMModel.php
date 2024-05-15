<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Model;
use Orkester\Persistence\Repository;

class StaticSentenceMMModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('staticsentencemm')
            ->attribute('idStaticSentenceMM', key: Key::PRIMARY)
            ->attribute('idFlickr30k', type: Type::INTEGER)
            ->attribute('idDocument', type: Type::INTEGER, key: Key::FOREIGN)
            ->attribute('idSentence', type: Type::INTEGER, key: Key::FOREIGN)
            ->attribute('idImageMM', type: Type::INTEGER, key: Key::FOREIGN)
            ->associationOne('sentence', model: SentenceModel::class, key: 'idSentence:idSentence')
            ->associationOne('imageMM', model: ImageMMModel::class, key: 'idImageMM:idImageMM')
            ->associationOne('document', model: DocumentModel::class, key: 'idDocument')
            ->associationMany('staticObjectSentenceMM', model: StaticObjectSentenceMMModel::class, keys: 'idStaticSentenceMM:idStaticSentenceMM');
    }

}
