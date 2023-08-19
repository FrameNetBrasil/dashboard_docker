<?php

namespace App\Models;

use App\Core\App;
use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\PersistenceManager;

class ConstructionModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('construction');
        self::attribute('idConstruction', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('active', type: Type::INTEGER);
        self::attribute('abstract', type: Type::INTEGER);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguageCxn', field: 'idLanguage');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationMany('relations', model: RelationModel::class, keys: 'idEntity:idEntity1');
        self::associationMany('inverseRelations', model: RelationModel::class, keys: 'idEntity:idEntity2');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('language', model: LanguageModel::class);

    }

    public static function getById(int $idConstruction): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        return (array) self::one([
            ['idConstruction', '=', $idConstruction],
            ['entries.idLanguage', '=', $idLanguage]
        ], [
            'idConstruction',
            'entry',
            'name',
            'description',
            'idEntity'
        ]);
    }

    public static function listByConstructionName(string $name = '', string|int|null $idLanguageCxn = null): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [];
        if ($idLanguageCxn) {
            $filters[] = ['idLanguageCxn', '=', $idLanguageCxn];
        }
        $filters[] = ['idLanguage', '=', $idLanguage];
        $filters[] = ['active', '=', 1];
        if ($name != '') {
            $filters[] = ['name', 'startswith', $name];
        }
        return self::list($filters, [
            'idConstruction',
            'name',
            'description as definition',
        ], 'name');
    }

    public static function listDirectRelations(int $idConstruction)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = (new ConstructionModel())->getCriteria()
            ->select(['relations.idEntityRelation', 'relations.idRelationType', 'relations.entry', 'relations.construction.name', 'relations.construction.idEntity', 'relations.construction.idConstruction'])
            ->where('idConstruction', '=', $idConstruction)
            ->where('relations.entry', 'IN', [
                'rel_inheritance_cxn',
                'rel_daughter_of'
            ])
            ->where('relations.construction.idLanguage', '=', $idLanguage)
            ->orderBy('relations.construction.name');
        return $criteria->treeResult('entry', 'name,idEntity,idConstruction,idEntityRelation,idRelationType');

    }

    public static function listInverseRelations(int $idConstruction)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = (new ConstructionModel())->getCriteria()
            ->select(['relations.idEntityRelation', 'relations.idRelationType', 'relations.entry', 'name', 'idEntity', 'idConstruction'])
            ->where('construction.idConstruction', '=', $idConstruction)
            ->where('relations.entry', 'IN', [
                'rel_inheritance_cxn',
                'rel_daughter_of'
            ])
            ->where('idLanguage', '=', $idLanguage)
            ->orderBy('name');
        return $criteria->treeResult('entry', 'name,idEntity,idConstruction,idEntityRelation,idRelationType');

    }

    public static function listCECoreSet(int $idConstruction)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $db = PersistenceManager::$capsule->getConnection('webtool');
        $paramsQuery = [
            'idConstruction1' => $idConstruction,
            'idConstruction2' => $idConstruction,
            'idLanguage1' => $idLanguage,
            'idLanguage2' => $idLanguage,
        ];

        $result = $db->select("
        SELECT e1.name ce1, e2.name ce2
        FROM view_relation r
          JOIN view_constructionelement ce1
            ON (r.idEntity1 = ce1.idEntity)
          JOIN entry e1
            ON (ce1.entry = e1.entry)
          JOIN view_constructionelement ce2
            ON (r.idEntity2 = ce2.idEntity)
          JOIN entry e2
            ON (ce2.entry = e2.entry)
          WHERE (r.relationtype = 'rel_coreset')
            AND (ce1.idConstruction     = :idConstruction1)
            AND (ce2.idConstruction     = :idConstruction2)
            AND (e1.idLanguage   = :idLanguage1)
            AND (e2.idLanguage   = :idLanguage2)
            ", $paramsQuery);

        $index = [];
        $i = 0;
        foreach ($result as $row) {
            $ce1 = $index[$row['ce1']] ?? '';
            $ce2 = $index[$row['ce2']] ?? '';
            if (($ce1 == '' && $ce2 == '')) {
                $i++;
                $index[$row['ce1']] == $i;
                $index[$row['ce2']] == $i;
            } else if ($ce1 == '') {
                $index[$row['ce1']] = $index[$row['ce2']];
            } else {
                $index[$row['ce2']] = $index[$row['ce1']];
            }
        }

        $ceCoreSet = [];
        foreach ($index as $ce => $i) {
            $ceCoreSet[$i][] = $ce;
        }

        return $ceCoreSet;
    }
}