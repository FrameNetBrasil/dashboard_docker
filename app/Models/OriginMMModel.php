<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;

class OriginMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('originmm');
        self::attribute('idOriginMM', key: Key::PRIMARY);
        self::attribute('origin');
    }

}