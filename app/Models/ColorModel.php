<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class ColorModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('color');
        self::attribute('idColor', key: Key::PRIMARY);
        self::attribute('name');
        self::attribute('rgbFg');
        self::attribute('rgbBg');
    }

}