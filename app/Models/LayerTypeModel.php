<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;

class LayerTypeModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('layertype');
        self::attribute('idLayerType', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('allowsApositional');
        self::attribute('isAnnotation');
        self::attribute('layerOrder');
        self::attribute('idLayerGroup', key: Key::FOREIGN);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('layerGroup', model: LayerGroupModel::class, key: 'idLayerGroup');
    }

}

