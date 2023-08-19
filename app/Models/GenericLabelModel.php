<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class GenericLabelModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('genericlabel');
        self::attribute('idGenericLabel', key: Key::PRIMARY);
        self::attribute('name');
        self::attribute('definition');
        self::attribute('example');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idColor', key: Key::FOREIGN);
        self::attribute('idLanguage', key: Key::FOREIGN);
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('color', model: ColorModel::class, key: 'idColor');
        self::associationOne('language', model: LanguageModel::class, key: 'idLanguage');
    }
}