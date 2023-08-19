<?php
namespace App\Models;

use Orkester\Persistence\Enum\Join;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewRelationModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        
        self::table('view_relation');
        self::attribute('idEntityRelation', key: Key::PRIMARY);
        self::attribute('domain');
        self::attribute('relationGroup');
        self::attribute('idRelationType', type: Type::INTEGER);
        self::attribute('relationType');
        self::attribute('prefix');
        self::attribute('idEntity1', type: Type::INTEGER);
        self::attribute('idEntity2', type: Type::INTEGER);
        self::attribute('idEntity3', type: Type::INTEGER);
        self::attribute('entity1Type');
        self::attribute('entity2Type');
        self::attribute('entity3Type');
        self::associationOne('relationType', model: RelationTypeModel::class, key: 'idRelationType');
        self::associationMany('entries', model: EntryModel::class, keys: 'entry:entry');
        self::associationOne('entity1', model: EntityModel::class, key: 'idEntity1');
        self::associationOne('entity2', model: EntityModel::class, key: 'idEntity2');
        self::associationOne('entity3', model: EntityModel::class, key: 'idEntity3', join: Join::LEFT);
    }

}
