<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class ParagraphModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('paragraph');
        self::attribute('idParagraph', key: Key::PRIMARY);
        self::attribute('documentOrder', type:Type::INTEGER);
        self::attribute('idDocument', key:Key::FOREIGN);
        self::associationMany('sentences', model: SentenceModel::class, keys: 'idParagraph');
        self::associationOne('document', model: DocumentModel::class, key: 'idDocument');
    }

}