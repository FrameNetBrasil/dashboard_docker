<?php

namespace App\Models;

use App\Services\AppService;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;

class LUModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('lu');
        self::attribute('idLU', key: Key::PRIMARY);
        self::attribute('name');
        self::attribute('senseDescription');
        self::attribute('active', type: Type::INTEGER);
        self::attribute('importNum', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idLanguage',reference: 'lemma.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('lemma', model: LemmaModel::class);
        self::associationOne('frame', model: FrameModel::class, key: 'idFrame');
//        self::associationOne('fe', model: FrameElementModel::class, key: 'incorporatedFE');
    }

//    public static function listByName(string $name = '') : array {
//        $idLanguage = AppService::getCurrentIdLanguage();
//        $filters = [];
//        $filters[] = ['lemma.idLanguage','=', $idLanguage];
//        $filters[] = ['frame.idLanguage','=', $idLanguage];
//        $filters[] = ['active','=', 1];
//        if ($name != '') {
//            $filters[] = ['name','startswith', $name];
//        }
//        return self::list($filters, [
//            'idLU',
//            'name',
//            'senseDescription as definition',
//            'idFrame',
//            'frame.name as frameName'
//        ], [['frame.name'],['name']]);
//
//    }
}
