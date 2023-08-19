<?php
namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewConstructionElementModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        self::table('view_constructionelement');
        self::attribute('idConstructionElement', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('active', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idColor', key: Key::FOREIGN);
        self::attribute('idConstruction', key: Key::FOREIGN);
        self::attribute('constructionEntry');
        self::attribute('constructionIdEntity', type: Type::INTEGER);
        self::associationOne('entries', model: EntryModel::class, key: 'entry');
        self::associationOne('construction', model: ConstructionModel::class, key: 'idConstruction');
        self::associationOne('color', model: ColorModel::class, key: 'idColor');
    }

}
