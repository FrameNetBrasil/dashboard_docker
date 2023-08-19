<?php

namespace App\Models;

use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Model;
use Orkester\Persistence\Enum\Key;

class DocumentModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('document');
        self::attribute('idDocument', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('author');
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationOne('corpus', model: CorpusModel::class, key: 'idCorpus');
        self::associationMany('paragraphs', model: ParagraphModel::class, keys: 'idDocument');
        self::associationMany('sentences', model: SentenceModel::class, associativeTable: 'document_sentence');
    }


}
