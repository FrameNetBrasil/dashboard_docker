<?php

namespace App\Models;

use App\Services\AppService;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Join;
use Orkester\Persistence\Enum\Key;

class RelationModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('entityrelation');
        self::attribute('idEntityRelation', key: Key::PRIMARY);
        self::attribute('entry', reference: 'relationType.entry');
        self::attribute('entity1Type', reference: 'entity1.type');
        self::attribute('entity2Type', reference: 'entity2.type');
        self::attribute('entity3Type', reference: 'entity3.type');
        self::associationOne('relationType', model: RelationTypeModel::class, key: 'idRelationType');
        self::associationMany('entries', model: EntryModel::class, keys: 'entry:entry');
        self::associationOne('entity1', model: EntityModel::class, key: 'idEntity1');
        self::associationOne('entity2', model: EntityModel::class, key: 'idEntity2');
        self::associationOne('entity3', model: EntityModel::class, key: 'idEntity3', join: Join::LEFT);
        self::associationOne('frame1', model: FrameModel::class, key: 'idEntity1:idEntity');
        self::associationOne('frame2', model: FrameModel::class, key: 'idEntity2:idEntity');
        self::associationOne('frame', model: FrameModel::class, key: 'idEntity2:idEntity');
        self::associationOne('construction1', model: ConstructionModel::class, key: 'idEntity1:idEntity'); 
        self::associationOne('construction2', model: ConstructionModel::class, key: 'idEntity2:idEntity');
        self::associationOne('construction', model: ConstructionModel::class, key: 'idEntity2:idEntity');
        self::associationOne('semanticType1', model: SemanticTypeModel::class, key: 'idEntity1:idEntity');
        self::associationOne('semanticType2', model: SemanticTypeModel::class, key: 'idEntity2:idEntity');
        self::associationOne('semanticType', model: SemanticTypeModel::class, key: 'idEntity2:idEntity');
        self::associationOne('constructionElement1', model: ConstructionElementModel::class, key: 'idEntity1:idEntity');
        self::associationOne('constructionElement2', model: ConstructionElementModel::class, key: 'idEntity2:idEntity');
        self::associationOne('constructionElement', model: ConstructionElementModel::class, key: 'idEntity2:idEntity');
        self::associationOne('frameElement1', model: FrameElementModel::class, key: 'idEntity1:idEntity');
        self::associationOne('frameElement2', model: FrameElementModel::class, key: 'idEntity2:idEntity');
        self::associationOne('frameElement', model: FrameElementModel::class, key: 'idEntity2:idEntity');
        self::associationOne('inverseFrame', model: FrameModel::class, key: 'idEntity1:idEntity');
        self::associationOne('inverseConstruction', model: ConstructionModel::class, key: 'idEntity1:idEntity');
        self::associationOne('inverseSemanticType', model: SemanticTypeModel::class, key: 'idEntity1:idEntity');
        self::associationOne('subtypeOfConcept', model: ConceptModel::class, key: 'idEntity2:idEntity');
        self::associationOne('associatedToConcept', model: ConceptModel::class, key: 'idEntity2:idEntity');
        self::associationOne('domain', model: DomainModel::class, key: 'idEntity2:idEntity');
        self::associationOne('layerType', model: LayerTypeModel::class, key: 'idEntity1:idEntity');
        self::associationOne('genericLabel', model: GenericLabelModel::class, key: 'idEntity2:idEntity');
    }

    public static function getFrameRelation(int $idEntityRelation): array
    {
        return self::getCriteria()
            ->select([
                'frame1.idFrame as idFrame1',
                'frame2.idFrame as idFrame2',
                'relationType.entry'
            ])->where('idEntityRelation', '=', $idEntityRelation)
            ->getResult()
            ->toArray()[0];
    }

    public static function listGLByLayerType(array $groups = [], string $POS = ''): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = self::getCriteria()
            ->select([
                'layerType.name as layerType',
                'layerType.idLayerType',
                'layerType.layerGroup.name as layerGroup',
                'layerType.entry as layerTypeEntry',
                'genericLabel.idGenericLabel',
                'genericLabel.name as label',
                'genericLabel.color.rgbFg',
                'genericLabel.color.rgbBg',
            ])->where('layerType.entries.idLanguage', '=', $idLanguage)
            ->where('genericLabel.idLanguage', '=', $idLanguage)
            ->orderBy('layerType.layerOrder');
        if (count($groups) > 0) {
            $criteria = $criteria->where('layerType.layerGroup.name', 'IN', $groups);
        }
        $result = $criteria
            ->getResult()
            ->all();
        if ($POS != '') {
            $posLayer = [
                'N' => 'lty_noun',
                'V' => 'lty_verb',
                'A' => 'lty_adj',
                'PREP' => 'lty_prep',
                'ADV' => 'lty_adv'
            ];
            $layers = [];
            foreach ($result as $row) {
                if ($row['layerGroup'] == 'Spec') {
                    if ($row['layerTypeEntry'] == $posLayer[$POS]) {
                        $layers[] = $row;
                    }
                } else {
                    $layers[] = $row;
                }
            }
        } else {
            $layers = $result;
        }
        $list = collect($layers)
            ->groupBy('layerType')
            ->all();
        return $list;
    }

}
