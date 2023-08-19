<?php

namespace App\Models;

use App\Core\App;
use App\Services\AppService;
use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;

class FrameElementModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('frameelement');
        self::attribute('idFrameElement', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('coreType');
        self::attribute('active', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idFrame', key: Key::FOREIGN);
        self::attribute('idColor', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('frame', model: FrameModel::class, key: 'idFrame');
        self::associationOne('color', model: ColorModel::class, key: 'idColor');
        self::associationMany('typeInstance', model: TypeInstanceModel::class, keys: 'coreType:entry');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationMany('relations', model: RelationModel::class, keys: 'idEntity:idEntity1');
    }

    public static function getById(int $idFrameElement): array {
        $idLanguage = AppService::getCurrentIdLanguage();
        return (array)self::one([
            ['idFrameElement','=', $idFrameElement],
            ['entries.idLanguage','=',$idLanguage]
        ],[
            'idFrameElement',
            'name',
            'description',
            'coreType',
            'idFrame',
            'idEntity',
            'color.rgbFg',
            'color.rgbBg'
        ]);
    }

//    public static function listByName(string $name = '') : array {
//        $idLanguage = AppService::getCurrentIdLanguage();
//        $filters = [];
//        $filters[] = ['idLanguage','=', $idLanguage];
//        $filters[] = ['frame.idLanguage','=', $idLanguage];
//        $filters[] = ['active','=', 1];
//        if ($name != '') {
//            $filters[] = ['name','startswith', $name];
//        }
//        return self::list($filters, [
//            'idFrameElement',
//            'name',
//            'description as definition',
//            'idFrame',
//            'frame.name as frameName'
//        ], [['frame.name'],['name']]);
//
//    }


    public static function listByIdFrame(int $idFrame): array {
        $idLanguage = AppService::getCurrentIdLanguage();
        return self::list([
            ['idFrame','=', $idFrame],
            ['entries.idLanguage','=',$idLanguage]
        ],[
            'idFrameElement',
            'entry',
            'name',
            'description',
            'coreType',
            'idFrame',
            'idEntity',
            'idColor',
            'color.rgbFg',
            'color.rgbBg'
        ], [['coreType'],['name']]);

    }

    public static function listFE2SemanticType(int $idFrameElement)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [
            ['relations.semanticType.entries.idLanguage','=', $idLanguage],
            ["idFrameElement","=",$idFrameElement]
        ];
        return (new FrameElementModel())->list( $filters, [
            'relations.semanticType.idEntity',
            'relations.semanticType.entry',
            'relations.semanticType.name'
        ]);
    }

    public static function listFE2FERelation(int $idFrameElement, string $relationType = 'rel_coreset')
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [
            ['relations.frameElement.entries.idLanguage','=', $idLanguage],
            ['relations.entry','=', $relationType],
            ["idFrameElement","=",$idFrameElement]
        ];
        return (new FrameElementModel())->list( $filters, [
            'relations.frameElement.idEntity',
            'relations.frameElement.entry',
            'relations.frameElement.name',
        ]);
    }

    public static function listCoreSet(int $idFrameElement)
    {
        return self::listFE2FERelation($idFrameElement, 'rel_coreset');
    }

    public static function listExcludes(int $idFrameElement)
    {
        return self::listFE2FERelation($idFrameElement, 'rel_excludes');
    }

    public static function listRequires(int $idFrameElement)
    {
        return self::listFE2FERelation($idFrameElement, 'rel_requires');
    }

    public static function listDirectRelations(int $idFrameElement)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = (new FrameElementModel())->getCriteria()
            ->select([
                'relations.idEntityRelation',
                'relations.entry',
                'relations.frameElement.name',
                'relations.frameElement.idEntity',
                'relations.frameElement.idFrameElement',
                'relations.frameElement.frame.idFrame',
                'relations.frameElement.frame.name as frameName',
                'frame.idEntity as frameIdEntity',
            ])
            ->where('idFrameElement','=', $idFrameElement)
            ->where('relations.entry', 'IN', [
                'rel_causative_of',
                'rel_inchoative_of',
                'rel_inheritance',
                'rel_perspective_on',
                'rel_precedes',
                'rel_see_also',
                'rel_subframe',
                'rel_using'
            ])
            ->where('relations.frameElement.idLanguage','=',$idLanguage)
            ->where('relations.frameElement.frame.idLanguage','=',$idLanguage)
            ->orderBy('relations.frameElement.name');
        return $criteria->treeResult('entry', 'name,idEntity,idFrameElement,idEntityRelation,idFrame,frameName,frameIdEntity');
    }

    public static function listInverseRelations(int $idFrameElement)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = (new FrameElementModel())->getCriteria()
            ->select([
                'relations.idEntityRelation',
                'relations.entry',
                'name',
                'idEntity',
                'idFrameElement',
                'frame.idFrame',
                'frame.name as frameName',
                'frame.idEntity as frameIdEntity',
            ])
            ->where('relations.frameElement.idFrameElement','=', $idFrameElement)
            ->where('relations.entry', 'IN', [
                'rel_causative_of',
                'rel_inchoative_of',
                'rel_inheritance',
                'rel_perspective_on',
                'rel_precedes',
                'rel_see_also',
                'rel_subframe',
                'rel_using'
            ])
            ->where('idLanguage','=',$idLanguage)
            ->where('frame.idLanguage','=',$idLanguage)
            ->orderBy('name');
        return $criteria->treeResult('entry', 'name,idEntity,idFrameElement,idEntityRelation,idFrame,frameName,frameIdEntity');
    }


}