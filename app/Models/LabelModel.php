<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class LabelModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('label');
        self::attribute('idLabel', key: Key::PRIMARY);
        self::attribute('startChar');
        self::attribute('endChar');
        self::attribute('multi');
        self::attribute('idLabelType', key: Key::FOREIGN);
        self::attribute('idLayer', key: Key::FOREIGN);
        self::attribute('idInstantiationType', key: Key::FOREIGN);
        self::associationOne('genericLabel', model: GenericLabelModel::class, key: 'idLabelType:idEntity');
        self::associationOne('frameElement', model: FrameElementModel::class, key: 'idLabelType:idEntity');
        self::associationOne('constructionElement', model: ConstructionElementModel::class, key: 'idLabelType:idEntity');
        self::associationOne('layer', model: LayerModel::class, key: 'idLayer');
        self::associationOne('instantiationType', model: TypeInstanceModel::class, key: 'idInstantiationType:idTypeInstance');
    }

}
