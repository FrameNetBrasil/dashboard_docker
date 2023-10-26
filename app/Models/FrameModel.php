<?php

namespace App\Models;

use App\Core\App;
use App\Services\AppService;
use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\PersistenceManager;

class FrameModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('frame');
        self::attribute('idFrame', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('active', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationMany('lus', model: LUModel::class, keys: 'idFrame');
        self::associationMany('fes', model: FrameElementModel::class, keys: 'idFrame');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationMany('relations', model: RelationModel::class, keys: 'idEntity:idEntity1');
        self::associationMany('inverseRelations', model: RelationModel::class, keys: 'idEntity:idEntity2');
    }

    public static function getById(int $idFrame): array {
        $idLanguage = AppService::getCurrentIdLanguage();
        return (array)self::one([
            ['idFrame','=', $idFrame],
            ['entries.idLanguage','=',$idLanguage]
        ],[
            'idFrame',
            'entry',
            'name',
            'description',
            'idEntity'
        ]);

    }

//    public static function listByName(string $name = '') : array {
//        $idLanguage = AppService::getCurrentIdLanguage();
//        $filters = [];
//        $filters[] = ['idLanguage','=', $idLanguage];
//        $filters[] = ['active','=', 1];
//        if ($name != '') {
//            $filters[] = ['name','startswith', $name];
//        }
//        return self::list($filters, [
//            'idFrame',
//            'name',
//            'description as definition'
//        ], 'name');
//    }

    public static function listFECoreSet(int $idFrame)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $db = PersistenceManager::$capsule->getConnection('webtool');
        $paramsQuery = [
            'idFrame1' => $idFrame,
            'idFrame2' => $idFrame,
            'idLanguage1' => $idLanguage,
            'idLanguage2' => $idLanguage,
        ];
        $result = $db->select("
        SELECT e1.name fe1, e2.name fe2
        FROM view_relation r
          JOIN view_frameelement fe1
            ON (r.idEntity1 = fe1.idEntity)
          JOIN entry e1
            ON (fe1.entry = e1.entry)
          JOIN view_frameelement fe2
            ON (r.idEntity2 = fe2.idEntity)
          JOIN entry e2
            ON (fe2.entry = e2.entry)
          WHERE (r.relationtype = 'rel_coreset')
            AND (fe1.idFrame     = :idFrame1)
            AND (fe2.idFrame     = :idFrame2)
            AND (e1.idLanguage   = :idLanguage1)
            AND (e2.idLanguage   = :idLanguage2)    
", $paramsQuery);
        $index = [];
        $i = 0;
        foreach ($result as $row) {
            $fe1 = $index[$row['fe1']] ?? '';
            $fe2 = $index[$row['fe2']] ?? '';
            if (($fe1 == '') && ($fe2 == '')) {
                $i++;
                $index[$row['fe1']] = $i;
                $index[$row['fe2']] = $i;
            } elseif ($fe1 == '') {
                $index[$row['fe1']] = $index[$row['fe2']];
            } else {
                $index[$row['fe2']] = $index[$row['fe1']];
            }
        }
        $feCoreSet = [];
        foreach ($index as $fe => $i) {
            $feCoreSet[$i][] = $fe;
        }
        return $feCoreSet;
    }

    public static function listDirectRelations(int $idFrame)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = (new FrameModel())->getCriteria()
            ->select(['relations.idEntityRelation','relations.idRelationType','relations.entry','relations.frame.name','relations.frame.idEntity','relations.frame.idFrame'])
            ->where('idFrame','=', $idFrame)
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
            ->where('relations.frame.idLanguage','=',$idLanguage)
            ->orderBy('relations.frame.name');
        return $criteria->treeResult('entry', 'name,idEntity,idFrame,idEntityRelation,idRelationType');
    }

    public static function listInverseRelations(int $idFrame)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = (new FrameModel())->getCriteria()
            ->select(['relations.idEntityRelation','relations.idRelationType','relations.entry','name','idEntity','idFrame'])
            ->where('relations.frame.idFrame','=', $idFrame)
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
            ->orderBy('name');
        return $criteria->treeResult('entry', 'name,idEntity,idFrame,idEntityRelation,idRelationType');
    }

}