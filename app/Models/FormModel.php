<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class FormModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('form');
        self::attribute('idForm', key: Key::PRIMARY);
        self::attribute('form');
        self::attribute('md5');
        self::attribute('idPOS', key: Key::FOREIGN);
        self::attribute('idLanguage', key: Key::FOREIGN);
        self::associationOne('pos', model: POSModel::class, key: 'idPOS');
        self::associationOne('language', model: LanguageModel::class, key: 'idLanguage');
        self::associationOne('entry', model: FormEntryModel::class, key: 'idForm');
        self::associationMany('udFeatures', model: UDFeatureModel::class, associativeTable: 'form_udfeature');
    }

}

