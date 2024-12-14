<?php

namespace Orkester\Persistence;

use Orkester\Persistence\Map\ClassMap;

class Mapping
{
    public static function map(string $className, ClassMap $classMap) {
        static::$className($classMap);
    }

}
