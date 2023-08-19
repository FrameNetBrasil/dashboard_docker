<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;

class ObjectSentenceMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('objectsentencemm');
        self::attribute('name');
        self::attribute('idObjectSentenceMM', key: Key::PRIMARY);
        self::attribute('startWord', type: Type::INTEGER);
        self::attribute('endWord', type: Type::INTEGER);
        self::attribute('idFrameElement', type: Type::INTEGER);
        self::attribute('idLU', type: Type::INTEGER);
        self::attribute('idLemma', type: Type::INTEGER);
        self::attribute('idSentenceMM', type: Type::INTEGER, key:Key::FOREIGN);
        self::attribute('idObjectMM', type: Type::INTEGER, key:Key::FOREIGN);
        self::associationOne('frameElement', model: FrameElementModel::class, key: 'idFrameElement');
        self::associationOne('sentenceMM', model: SentenceMMModel::class, key: 'idSentenceMM');
    }

}