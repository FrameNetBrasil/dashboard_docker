<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;

class AnnotationSetModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('annotationset');
        self::attribute('idAnnotationSet', key: Key::PRIMARY);
        self::attribute('idSentence', key: Key::FOREIGN);
        self::attribute('idAnnotationStatus', key: Key::FOREIGN);
        self::attribute('idEntityRelated', key: Key::FOREIGN);
        self::attribute('idEntityLU', field: 'idEntityRelated' ,type:Type::INTEGER);
        self::attribute('idEntityCxn', field: 'idEntityRelated' ,type:Type::INTEGER);
        self::associationMany('lu', model: LUModel::class, keys: 'idEntityLU:idEntity');
        self::associationMany('cxn', model: ConstructionModel::class, keys: 'idEntityCxn:idEntity');
        self::associationOne('sentence', model: SentenceModel::class, key: 'idSentence');
        self::associationOne('annotationStatus', model: TypeInstanceModel::class, key: 'idAnnotationStatus:idTypeInstance');
        self::associationMany('layers', model: LayerModel::class, keys: 'idAnnotationSet');
    }

}
