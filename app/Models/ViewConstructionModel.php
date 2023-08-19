<?php
namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewConstructionModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        
        self::table('view_construction');
        self::attribute('idConstruction', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('active', type: Type::INTEGER);
        self::attribute('idLanguage', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationOne('entity', model: EntityModel::class, key: 'idEentity');
        self::associationMany('ces', model: ViewConstructionElementModel::class, keys: 'idConstruction');
        self::associationMany('annotationSets', model: ViewAnnotationSetModel::class, keys: 'idConstruction');
    }

}
