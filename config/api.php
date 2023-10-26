<?php

use App\Middleware\JsonApiMiddleware;
use App\Models\AnnotationSetModel;
use App\Services\AuthService;
use App\Services\AuthUserService;
use App\Services\DashboardService;
use App\Services\GrapherFrameService;
use App\Services\LanguageService;
use App\Models\DocumentMMModel;
use App\Models\ImageMMModel;
use App\Models\ObjectFrameMMModel;
use App\Models\ObjectMMModel;
use App\Models\ObjectSentenceMMModel;
use App\Models\OriginMMModel;
use App\Models\SentenceMMModel;
use App\Models\StatusMMModel;
use App\Models\ColorModel;
use App\Models\ConceptModel;
use App\Models\ConstraintModel;
use App\Models\ConstructionElementModel;
use App\Models\ConstructionModel;
use App\Models\CorpusModel;
use App\Models\DocumentModel;
use App\Models\DomainModel;
use App\Models\EntityModel;
use App\Models\EntryModel;
use App\Models\FrameElementModel;
use App\Models\FrameModel;
use App\Models\GenericLabelModel;
use App\Models\GenreModel;
use App\Models\GenreTypeModel;
use App\Models\GroupModel;
use App\Models\LabelFECETargetModel;
use App\Models\LabelModel;
use App\Models\LanguageModel;
use App\Models\LayerGroupModel;
use App\Models\LayerModel;
use App\Models\LayerTypeModel;
use App\Models\LemmaModel;
use App\Models\LexemeModel;
use App\Models\LUModel;
use App\Models\ParagraphModel;
use App\Models\POSModel;
use App\Models\QualiaModel;
use App\Models\RelationGroupModel;
use App\Models\RelationModel;
use App\Models\SemanticTypeModel;
use App\Models\SentenceModel;
use App\Models\TimelineModel;
use App\Models\TypeModel;
use App\Models\UDFeatureModel;
use App\Models\UDPOSModel;
use App\Models\UDRelationModel;
use App\Models\UserModel;
use App\Models\WordFormModel;
use App\Services\FrameService;
use App\Services\MultimodalService;
use App\Services\ReportMultimodalService;
use App\Services\UIService;
use Orkester\Resource\BasicResource;

return [
    'api' => [
        'root' => '/api',
        'middleware' => JsonApiMiddleware::class,
        'resources' => [
        ],
        'services' => [
            'ui' => UIService::class,
            'auth' => AuthService::class,
            'authuser' => AuthUserService::class,
            'language' => LanguageService::class,
            'dashboard' => DashboardService::class,
            'frame' => FrameService::class,
            'reportMultimodal' => ReportMultimodalService::class,
            'grapherFrame' => GrapherFrameService::class,
        ]
    ],
    'resources' => [
        'annotationsets' => fn() => new BasicResource(AnnotationSetModel::class),
        'ces' => fn() => new BasicResource(ConstructionElementModel::class),
        'colors' => fn() => new BasicResource(ColorModel::class),
        'concepts' => fn() => new BasicResource(ConceptModel::class),
        'constraints' => fn() => new BasicResource(ConstraintModel::class),
        'constructions' => fn() => new BasicResource(ConstructionModel::class),
        'corpora' => fn() => new BasicResource(CorpusModel::class),
        'documents' => fn() => new BasicResource(DocumentModel::class),
        'domains' => fn() => new BasicResource(DomainModel::class),
        'entities' => fn() => new BasicResource(EntityModel::class),
        'entries' => fn() => new BasicResource(EntryModel::class),
        'fes' => fn() => new BasicResource(FrameElementModel::class),
        'frames' => fn() => new BasicResource(FrameModel::class),
        'genericlabels' => fn() => new BasicResource(GenericLabelModel::class),
        'genres' => fn() => new BasicResource(GenreModel::class),
        'genretypes' => fn() => new BasicResource(GenreTypeModel::class),
        'groups' => fn() => new BasicResource(GroupModel::class),
        'labelfecetarget' => fn() => new BasicResource(LabelFECETargetModel::class),
        'labels' => fn() => new BasicResource(LabelModel::class),
        'languages' => fn() => new BasicResource(LanguageModel::class),
        'layergroups' => fn() => new BasicResource(LayerGroupModel::class),
        'layers' => fn() => new BasicResource(LayerModel::class),
        'layertypes' => fn() => new BasicResource(LayerTypeModel::class),
        'lemmas' => fn() => new BasicResource(LemmaModel::class),
        'lexemes' => fn() => new BasicResource(LexemeModel::class),
        'lus' => fn() => new BasicResource(LUModel::class),
        'paragraphs' => fn() => new BasicResource(ParagraphModel::class),
        'pos' => fn() => new BasicResource(POSModel::class),
        'qualias' => fn() => new BasicResource(QualiaModel::class),
        'relationgroups' => fn() => new BasicResource(RelationGroupModel::class),
        'relations' => fn() => new BasicResource(RelationModel::class),
        'semantictypes' => fn() => new BasicResource(SemanticTypeModel::class),
        'sentences' => fn() => new BasicResource(SentenceModel::class),
        'timelines' => fn() => new BasicResource(TimelineModel::class),
        'types' => fn() => new BasicResource(TypeModel::class),
        'udfeatures' => fn() => new BasicResource(UDFeatureModel::class),
        'udpos' => fn() => new BasicResource(UDPOSModel::class),
        'udrelations' => fn() => new BasicResource(UDRelationModel::class),
        'users' => fn() => new BasicResource(UserModel::class),
        'wordforms' => fn() => new BasicResource(WordformModel::class),
        //
        //charon - singular
        //
        'documentmm' => fn() => new BasicResource(DocumentMMModel::class),
        'imagemm' => fn() => new BasicResource(ImageMMModel::class),
        'objectmm' => fn() => new BasicResource(ObjectMMModel::class),
        'objectframemm' => fn() => new BasicResource(ObjectFrameMMModel::class),
        'objectsentencemm' => fn() => new BasicResource(ObjectSentenceMMModel::class),
        'originmm' => fn() => new BasicResource(OriginMMModel::class),
        'sentencemm' => fn() => new BasicResource(SentenceMMModel::class),
        'statusmm' => fn() => new BasicResource(StatusMMModel::class),
    ],
    'services' => [
        'auth0Login' => [AuthUserService::class, 'auth0Login'],
        'frameSearch' => [FrameService::class, 'search'],
        'mmReportByTimeInterval' => [MultimodalService::class, 'mmReportByTimeInterval'],
        'mmReportSentences' => [MultimodalService::class, 'mmReportSentences'],
        'mmReportBySentence' => [MultimodalService::class, 'mmReportBySentence'],
        'mmReportByPointInTime' => [MultimodalService::class, 'mmReportByPointInTime'],
        'mmReportByImageText' => [MultimodalService::class, 'mmReportByImageText'],
        'mmReportSankey' => [MultimodalService::class, 'mmReportSankey'],
        'mmObjectMMListForDynamicAnnotation' => [MultimodalService::class, 'listObjectMMForDynamicAnnotation'],
    ],
];

