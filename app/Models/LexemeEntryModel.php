<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class LexemeEntryModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('lexemeentry');
        self::attribute('idLexemeEntry', key: Key::PRIMARY);
        self::attribute('lexemeOrder', type: Type::INTEGER);
        self::attribute('breakBefore', type: Type::INTEGER);
        self::attribute('headWord', type: Type::INTEGER);
        self::associationOne('lemma', model: LemmaModel::class, key: "idLemma");
        self::associationOne('lexeme', model: LexemeModel::class, key: "idLexeme");
        self::associationOne('wordForm', model: WordFormModel::class, key: "idWordForm");
    }
}