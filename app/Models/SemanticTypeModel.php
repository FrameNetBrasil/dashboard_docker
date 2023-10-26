<?php

namespace App\Models;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class SemanticTypeModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('semantictype');
        self::attribute('idSemanticType', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('idEntity', type: Type::INTEGER);
        self::attribute('idDomain', type: Type::INTEGER);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class, key: 'idEntity');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationMany('relations', model: RelationModel::class, keys: 'idEntity:idEntity1');
        self::associationMany('inverseRelations', model: RelationModel::class, keys: 'idEntity:idEntity2');

    }

    public static function listByName(string $name = '') : array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [];
        $filters[] = ['idLanguage', '=', $idLanguage];
        if ($name != '') {
            $filters[] = ['name', 'startswith', $name];
        }
        return self::list($filters, [
            'idSemanticType',
            'name',
            'description as definition',
        ], 'name');
    }

    public static function getById($idSemanticType)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        return (array) self::one([
            ['idSemanticType', '=', $idSemanticType],
            ['entries.idLanguage', '=', $idLanguage]
        ], [
            'idSemanticType',
            'entry',
            'name',
            'description',
            'idEntity'
        ]);
    }

    public static function listDirectRelations($idSemanticType)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria= (new SemanticTypeModel())->getCriteria()
        ->select(['relations.idEntityRelation', 'relations.idRelationType', 'relations.entry', 'relations.semanticType.name', 'relations.semanticType.idEntity', 'relations.semanticType.idSemanticType'])
        ->where('idSemanticType', '=', $idSemanticType)
        ->where('relations.entry', 'IN', [
            'rel_subtypeof'
        ])
        ->where('relations.semanticType.idLanguage', '=', $idLanguage)
        ->orderBy('relations.semanticType.name');
        return $criteria->treeResult('entry', 'name,idEntity,idSemanticType,idEntityRelation,idRelationType');
    }

    public static function listInverseRelations($idSemanticType)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria= (new SemanticTypeModel())->getCriteria()
        ->select(['relations.idEntityRelation', 'relations.idRelationType', 'relations.entry', 'name', 'idEntity', 'idSemanticType'])
        ->where('relations.semanticType.idSemanticType', '=', $idSemanticType)
        ->where('relations.entry', 'IN', [
            'rel_subtypeof'
        ])
        ->where('idLanguage', '=', $idLanguage)
        ->orderBy('name');
        return $criteria->treeResult('entry', 'name,idEntity,idSemanticType,idEntityRelation,idRelationType');
    }
}

