<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class ConceptModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('concept');
        self::attribute('idConcept', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('keyword');
        self::attribute('aka');
        self::attribute('idTypeInstance', key: Key::FOREIGN);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('typeInstance', model: TypeInstanceModel::class);
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationMany('relations', model: RelationModel::class, keys: 'idEntity:idEntity1');
        self::associationMany('inverseRelations', model: RelationModel::class, keys: 'idEntity:idEntity2');
    }

}