<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;

class ObjectMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('objectmm');
        self::attribute('idObjectMM', key: Key::PRIMARY);
        self::attribute('name');
        self::attribute('startFrame', type: Type::INTEGER);
        self::attribute('endFrame', type: Type::INTEGER);
        self::attribute('startTime', type: Type::FLOAT);
        self::attribute('endTime', type: Type::FLOAT);
        self::attribute('status', type: Type::INTEGER);
        self::attribute('origin', type: Type::INTEGER);
        self::attribute('idDocumentMM', type: Type::INTEGER);
        self::attribute('idFrameElement', type: Type::INTEGER);
        self::attribute('idFlickr30k', type: Type::INTEGER);
        self::attribute('idImageMM', type: Type::INTEGER);
        self::attribute('idLemma', type: Type::INTEGER);
        self::attribute('idLU', type: Type::INTEGER);
        self::associationOne('documentMM', model: DocumentMMModel::class, key: 'idDocumentMM');
        self::associationOne('frameElement', model: FrameElementModel::class, key: 'idFrameElement');
        self::associationMany('objectFrames', model: ObjectFrameMMModel::class, keys: 'idObjectMM');

    }

}