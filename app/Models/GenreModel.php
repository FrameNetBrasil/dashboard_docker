<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class GenreModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('genre');
        self::attribute('idGenre', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::attribute('idGenreType', key: Key::FOREIGN);
        self::associationOne('genreType', model: GenreTypeModel::class);
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
    }

}
