<?php

namespace App\Models;

use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class ASCommentsModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('ascomments');
    }

}


