<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;

class LayerModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('layer');
        self::attribute('idLayer', key: Key::PRIMARY);
        self::attribute('rank');
        self::attribute('idLayerType', key: Key::FOREIGN);
        self::attribute('idAnnotationSet', key: Key::FOREIGN);
        self::associationOne('layerType', model: LayerTypeModel::class, key: 'idLayerType');
        self::associationOne('annotationSet', model: AnnotationSetModel::class, key: 'idAnnotationSet');
        self::associationMany('labels', model: LabelModel::class, keys: 'idLayer');
    }

}

