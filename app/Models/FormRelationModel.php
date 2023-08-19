<?php

namespace App\Models;

use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Join;
use Orkester\Persistence\Enum\Key;

class FormRelationModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('formrelation');
        self::attribute('idFormRelation', key: Key::PRIMARY);
        self::attribute('idRelationType', key: Key::FOREIGN);
        self::attribute('idForm1', key: Key::FOREIGN);
        self::attribute('idForm2', key: Key::FOREIGN);
        self::associationOne('relationType', model: RelationTypeModel::class, key: 'idRelationType');
        self::associationOne('form1', model: FormModel::class, key: 'idForm1:idForm');
        self::associationOne('form2', model: FormModel::class, key: 'idForm2:idForm');
    }

}
