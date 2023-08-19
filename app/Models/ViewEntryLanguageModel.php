<?php
namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewEntryLanguageModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        
        self::table('view_entrylanguage');
        self::attribute('idEntry', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('name');
        self::attribute('description');
        self::attribute('language');
        self::attribute('idLanguage', key: Key::FOREIGN);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::associationOne('language', model: LanguageModel::class);
    }

}
