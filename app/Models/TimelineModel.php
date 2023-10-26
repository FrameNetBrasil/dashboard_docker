<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class TimelineModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('timeline');
        self::attribute('idTimeline', key: Key::PRIMARY);
        self::attribute('timeline');
        self::attribute('numOrder', type: Type::INTEGER);
        self::attribute('tlDateTime', type: Type::DATETIME);
        self::attribute('author');
        self::attribute('operation');

    }

}
