<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class FormEntryModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('formentry');
        self::attribute('idFormEntry', key: Key::PRIMARY);
        self::attribute('idForm', key: Key::FOREIGN);
        self::attribute('idFormPart', key: Key::FOREIGN);
        self::attribute('lexemeOrder', type: Type::INTEGER);
        self::attribute('breakBefore', type: Type::INTEGER);
        self::attribute('headWord', type: Type::INTEGER);
        self::attribute('inflected', type: Type::INTEGER);
        self::associationOne('form', model: FormModel::class, key: "idForm");
        self::associationOne('formPart', model: FormModel::class, key: "idFormPart");
    }
}