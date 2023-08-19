<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewAnnotationStatusTypeModel extends Model {
    public static function map(ClassMap $classMap): void
    {
        
        self::table('view_annotationstatustype');
        self::attribute('idType', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('info');
        self::attribute('flag', type:Type::INTEGER);
        self::attribute('idColor',type:Type::INTEGER);
        self::attribute('idEntity',type:Type::INTEGER);
        self::associationOne('entries', model: ViewEntryLanguageModel::class, key: 'entry');
        self::associationOne('color', model: ColorModel::class, key: 'idColor');
    }
}
