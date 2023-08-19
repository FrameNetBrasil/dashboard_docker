<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;

class SentenceMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('sentencemm');
        self::attribute('idSentenceMM', key: Key::PRIMARY);
        self::attribute('startTimestamp');
        self::attribute('endTimestamp');
        self::attribute('startTime', type: Type::FLOAT);
        self::attribute('endTime', type: Type::FLOAT);
        self::attribute('origin', type: Type::INTEGER);
        self::attribute('idFlickr30k', type: Type::INTEGER);
        self::attribute('idDocumentMM', type: Type::INTEGER, key: Key::FOREIGN);
        self::attribute('idSentence', type: Type::INTEGER, key: Key::FOREIGN);
        self::attribute('idOriginMM', type: Type::INTEGER, key: Key::FOREIGN);
        self::attribute('idImageMM', type: Type::INTEGER, key: Key::FOREIGN);
        self::associationOne('sentence', model: SentenceModel::class, key: 'idSentence:idSentence');
        self::associationOne('imageMM', model: ImageMMModel::class, key: 'idImageMM:idImageMM');
        self::associationMany('objectSentenceMM', model: ObjectSentenceMMModel::class, keys: 'idSentenceMM:idSentenceMM');
    }

}