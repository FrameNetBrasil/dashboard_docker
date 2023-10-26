<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class StatusMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('statusmm');
        self::attribute('idStatusMM', key: Key::PRIMARY);
        self::attribute('file');
        self::attribute('video', type: Type::INTEGER);
        self::attribute('audio', type: Type::INTEGER);
        self::attribute('speechToText', type: Type::FLOAT);
        self::attribute('frames', type: Type::INTEGER);
        self::attribute('yolo', type: Type::INTEGER);
        self::attribute('idDocumentMM', type: Type::INTEGER, key: Key::FOREIGN);
    }

}