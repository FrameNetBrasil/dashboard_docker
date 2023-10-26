<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use App\Services\AppService;
class ConstructionElementModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('constructionelement');
        self::attribute('idConstructionElement', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('active', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('color', model: ColorModel::class, key: 'idColor');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationMany('relations', model: RelationModel::class, keys: 'idEntity:idEntity1');
    }

    public static function getById(int $idConstructionElement): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        return (array) self::one([
            ['idConstructionElement', '=', $idConstructionElement],
            ['entries.idLanguage', '=', $idLanguage]
        ], [
            'idConstructionElement',
            'name',
            'description',
            'idConstruction',
            'idEntity',
            'color.rgbFg',
            'color.rgbBg'
        ]);
    }

    public static function listByIdConstruction(int $idConstruction): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        return self::list([
            ['idConstruction', '=', $idConstruction],
            ['entries.idLanguage', '=', $idLanguage]
        ],[
            'idConstructionElement',
            'entry',
            'name',
            'description',
            'entry',
            'idConstruction',
            'idEntity',
            'idColor',
            'color.rgbFg',
            'color.rgbBg'
        ]);
    }

    public static function listCE2SemanticType(int $idConstructionElement)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [
            ['relations.semanticType.entries.idLanguage', '=', $idLanguage],
            ["idConstructionElement", "=", $idConstructionElement]
        ];
        return(new ConstructionElementModel())->list( $filters, [
            'relations.constructionElement.idEntity',
            'relations.constructionElement.entry',
            'relations.constructionElement.name',
        ]);
    }

    public static function listCE2CERelation(int $idConstructionElement, string $relationType = 'rel_coreset')
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [
            ['relations.constructionElement.entries.idLanguage', '=', $idLanguage],
            ['relations.entry', '=', $relationType],
            ["idConstructionElement", "=", $idConstructionElement]
        ];
        return (new ConstructionElementModel())->list($filters, [
            'relations.constructionElement.idEntity',
            'relations.constructionElement.entry',
            'relations.constructionElement.name',
        ]);
    }

    public static function listCoreSet(int $idConstructionElement)
    {
        return self::listCE2CERelation($idConstructionElement, 'rel_coreset');
    }

    public static function listExcludes(int $idConstructionElement)
    {
        return self::listCE2CERelation($idConstructionElement, 'rel_excludes');
    }

    public static function listRequires(int $idConstructionElement)
    {
        return self::listCE2CERelation($idConstructionElement, 'rel_requires');
    }

}

