<?php

namespace App\Models;

use App\Services\AppService;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;

class DomainModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('domain');
        self::attribute('idDomain', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class, key: 'idEntity');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
    }

    public function listForSelection(): array {
        return $this->list(['idLanguage','=', AppService::getCurrentIdLanguage()],['idDomain as id','name'],'name');
    }

}
