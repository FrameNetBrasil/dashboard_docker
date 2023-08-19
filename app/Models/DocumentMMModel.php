<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Model;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;


class DocumentMMModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('documentmm');
        self::attribute('idDocumentMM', key: Key::PRIMARY);
        self::attribute('name', reference: 'document.name');
        self::attribute('title');
        self::attribute('originalFile');
        self::attribute('sha1Name');
        self::attribute('audioPath');
        self::attribute('videoPath');
        self::attribute('videoWidth', type: Type::INTEGER);
        self::attribute('videoHeight', type: Type::INTEGER);
        self::attribute('alignPath');
        self::attribute('flickr30k');
        self::attribute('idDocument', type: Type::INTEGER, key: Key::FOREIGN);
        self::attribute('idLanguage', type: Type::INTEGER, key: Key::FOREIGN);
        self::associationOne('document', model: DocumentModel::class, key: 'idDocument:idDocument');
    }
}