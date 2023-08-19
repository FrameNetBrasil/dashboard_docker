<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;

class ImageMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('imagemm');
        self::attribute('idImageMM', key: Key::PRIMARY);
        self::attribute('name');
        self::attribute('width', type: Type::INTEGER);
        self::attribute('height', type: Type::INTEGER);
        self::attribute('depth', type: Type::FLOAT);
        self::attribute('imagePath');
    }

}