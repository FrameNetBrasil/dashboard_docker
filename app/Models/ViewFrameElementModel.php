<?php
namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewFrameElementModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        self::table('view_frameelement');
        self::attribute('idFrameElement', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('typeEntry');
        self::attribute('frameEntry');
        self::attribute('frameIdEntity', type: Type::INTEGER);
        self::attribute('active', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idFrame', key: Key::FOREIGN);
        self::attribute('idColor', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationOne('frame', model: FrameModel::class, key: 'idFrame');
        self::associationOne('color', model: ColorModel::class, key: 'idColor');
        self::associationMany('labels', model: LabelModel::class, keys: 'idFrameElement:idLabelType');
    }

}
