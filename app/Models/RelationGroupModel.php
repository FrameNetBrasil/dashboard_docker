<?php

namespace App\Models;

use App\App;
use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class RelationGroupModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('relationgroup');
        self::attribute('idRelationGroup', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationMany('entries', model: EntryModel::class, keys: 'entry:entry');
        self::associationOne('entity', model: EntityModel::class);
    }

}

