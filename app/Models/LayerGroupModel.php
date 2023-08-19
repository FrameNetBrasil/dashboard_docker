<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;

class LayerGroupModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('layergroup');
        self::attribute('idLayerGroup', key: Key::PRIMARY);
        self::attribute('name');
        self::associationMany('layerType', model: LayerTypeModel::class, keys: 'idLayerGroup');
    }

}