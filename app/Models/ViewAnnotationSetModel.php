<?php
namespace App\Models;

use App\Models\ViewEntryLanguage;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class ViewAnnotationSetModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        
        self::table('view_annotationset');
        self::attribute('idAnnotationSet', key: Key::PRIMARY);
        self::attribute('idSentence', key: Key::FOREIGN);
        self::attribute('entry');
        self::attribute('idAnnotationStatus', key: Key::FOREIGN);
        self::attribute('idLU');
        self::attribute('idEntityLU');
        self::attribute('idConstruction');
        self::attribute('idEntityCxn');
        self::associationMany('lu', model: LUModel::class, keys: 'idEntityLU:idEntity');
        self::associationMany('cxn', model: ConstructionModel::class, keys: 'idEntityCxn:idEntity');
        self::associationOne('entries', model: ViewEntryLanguageModel::class, key: 'entry');
        self::associationOne('sentence', model: SentenceModel::class, key: 'idSentence');
        self::associationOne('annotationStatusType', model: ViewAnnotationStatusTypeModel::class, key: 'entry');
        self::associationMany('layers', model: LayerModel::class, keys: 'idAnnotationSet');
    }

}
