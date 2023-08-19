<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class LexemeModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('lexeme');
        self::attribute('idLexeme', key: Key::PRIMARY);
        self::attribute('name');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('pos', model: POSModel::class, key: 'idPOS');
        self::associationOne('udpos', model: UDPOSModel::class);
        self::associationOne('language', model: LanguageModel::class);
        self::associationMany('lemmas', model: LemmaModel::class, associativeTable: 'lexemeentry');
        self::associationMany('lexemeentries', model: LexemeEntryModel::class, keys: 'idLexeme');
        self::associationMany('wordforms', model: WordFormModel::class, keys: 'idLexeme');
    }
}

