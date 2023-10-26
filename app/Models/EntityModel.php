<?php

namespace App\Models;

use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;

class EntityModel extends Model
{
    public static $entityModel = [
        'AS' => 'TypeInstanceModel', //annotationset type
        'C5' => 'TypeInstanceModel', //- C5 type
        'CE' => 'ConstructionElementModel', // construction element
        'CN' => 'ConstraintModel', // constraint
        'CP' => 'ConceptModel',// - concept
        'CR' => 'CorpusModel', // corpus
        'CT' => 'TypeInstanceModel', // coretype
        'CX' => 'ConstructionModel', // construction
        'DC' => 'DocumentModel', // document
        'DO' => 'DomainModel', // domain
        'FE' => 'FrameElementModel', // frame element
        'FR' => 'FrameModel', // frame
        'GL' => 'GenericLabelModel', // generic label
        'GR' => 'GenreModel', // genre
        'GT' => 'GenreTypeModel', // genretype
        'IT' => 'TypeInstanceModel', // instantiationtype
        'LM' => 'LemmaModel', // lemma
        'LT' => 'LayerTypeModel', // layertype
        'LU' => 'LUModel', // lu
        'LX' => 'LexemeModel', // lexeme
        'PS' => 'POSModel', // pos
        'PT' => 'TypeInstanceModel', // concept type
        'QL' => 'TypeInstanceModel', // qualia type
        'QR' => 'QualiaModel', // qualia relation (tabela qualia)
        'RG' => 'RelationGroupModel', // relationgroup
        'RT' => 'RelationTypeModel', //'relationtype
        'ST' => 'SemanticTypeModel', //- semantic type
        'TP' => 'TemplateModel', // template
        'TY' => 'TypeModel', // type
        'UF' => 'UDFeatureModel', // ud feature
        'UP' => 'UDPOSModel', // ud pos
        'UR' => 'UDRelationModel', // ud relation
        'UV' => 'UDFunctionModel', // ud function
        'WF' => 'WordformModel', // wordform
    ];

    public static function map(ClassMap $classMap): void
    {
        self::table('entity');
        self::attribute('idEntity', key: Key::PRIMARY);
        self::attribute('type');
        self::attribute('alias');
    }
}