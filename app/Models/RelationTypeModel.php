<?php

namespace App\Models;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class RelationTypeModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('relationtype');
        self::attribute('idRelationType', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('prefix');
        self::attribute('nameEntity1');
        self::attribute('nameEntity2');
        self::attribute('nameEntity3');
        self::attribute('idDomain', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idTypeInstance', key: Key::FOREIGN);
        self::attribute('idRelationGroup', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('typeInstance', model: TypeInstanceModel::class);
        self::associationOne('relationGroup', model: RelationGroupModel::class, key: 'idRelationGroup');
        self::associationMany('entries', model: EntryModel::class, keys: 'entry:entry');

    }

    public static function listForFrameGrapher(): array {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [];
        $filters[] = ['idLanguage','=', $idLanguage];
        $filters[] = ['idRelationGroup','=', 2];
        return self::list($filters, [
            'idRelationType',
            'name',
            '1 as selected'
        ], 'name');
    }

}
