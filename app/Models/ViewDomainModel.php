<?php
namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewDomainModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        
        self::table('view_domain');
        self::attribute('idDomain', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::attribute('idEntityRel', key: Key::FOREIGN);
        self::attribute('entityType');
        self::attribute('nameRel');
    }

}
