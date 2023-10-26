<?php

return [

    'lang' => 1,
    'language' => 'pt',
    'defaultIdLanguage' => 1,
    'pageTitle' => 'FNBr Dashboard',
    'mainTitle' => 'Dashboard',
    'login' => [
        'handler' => 'auth0',
        'AUTH0_CLIENT_ID' => env('AUTH0_CLIENT_ID'),
        'AUTH0_CLIENT_SECRET' => env('AUTH0_CLIENT_SECRET'),
        'AUTH0_COOKIE_SECRET' => env('AUTH0_COOKIE_SECRET'),
        'AUTH0_DOMAIN' => env('AUTH0_DOMAIN'),
        'AUTH0_CALLBACK_URL' => env('AUTH0_CALLBACK_URL'),
        'AUTH0_BASE_URL' => env('AUTH0_BASE_URL'),
//        'AUTH0_AUDIENCE' =>  $_ENV['AUTH0_AUDIENCE'],
//        'AUTH0_MM_CLIENT_ID' =>  $_ENV['AUTH0_MM_CLIENT_ID'],
//        'AUTH0_MM_CLIENT_SECRET' => $_ENV['AUTH0_MM_CLIENT_SECRET'],
    ],
    'actions' => [
        'menu' => ['mainPanel', '/main/main', 'fnbrIconForm', '', [
            'admin' => ['Admin', '/admin/main', 'wt-icon-admin', 'ADMIN', [
//            'users' => ['Users', '/auth/user/main', 'material wt-icon-user', 'ADMIN', []],
//            'annostatus' => ['Anno Status', '/admin/annostatus/main', 'material wt-icon-admin', 'ADMIN', []],
//            'domain' => ['Domain', '/admin/domain/main', 'material wt-icon-admin', 'ADMIN', []],
//            'relationgroup' => ['Relation Group', '/admin/relationgroup/main', 'material wt-icon-admin', 'ADMIN',  []],
//            'type' => ['Type', 'admin/type/main', 'material wt-icon-admin', 'ADMIN', []],
//            'relationgroupstructure' => ['Relation Group', '/structure/relationgroup/main', 'material wt-icon-structure', 'ADMIN',  []],
//            'relationtypestructure' => ['Relation Type', '/structure/relationtype/main', 'material wt-icon-structure', 'ADMIN', []],
//            'layergroupstructure' => ['Layer Group', '/structure/layergroup/main', 'material wt-icon-structure', 'ADMIN',  []],
//            'layertypestructure' => ['Layer Type', '/structure/layertype/main', 'material wt-icon-structure', 'ADMIN', []],
//            'constrainttype' => ['Constraint Type', '/structure/constrainttype/main', 'material wt-icon-structure', 'ADMIN', []],
//            'genre' => ['Genre', '/structure/genre/main', 'material wt-icon-structure', 'ADMIN',  []],
//            'conceptstructure' => ['Concept', '/structure/concept/main', 'material wt-icon-concept', 'ADMIN', []],
//            'semantictypestructure' => ['Semantic Type', '/structure/semantictype/main', 'material wt-icon-st', 'ADMIN', []],
            ]],
            'structure' => ['Structure', '', '', '', [
                'frame' => ['Frames', '/frame/main', 'wt-icon-frame', '', []],
                'cxn' => ['Constructions', '/cxn/main', 'wt-icon-cxn', '', []],
                'semanticType' => ['Semantic Types', '/semanticType/main', 'wt-icon-cxn', '', []]
            ]],

//        'frame' => ['Frames', '/frame/main', 'wt-icon-frame', '', [
//            'framereport' => ['Frames', '/report/frame/main', 'material wt-icon-frame', '',  []],
//            'domaingrapher' => ['Grapher by Domain', '/grapher/domain/main', 'material wt-icon-grapher', '',  []],
//            'fullgrapher' => ['Grapher Frames & CxN', '/grapher/grapher/main', 'material wt-icon-grapher', '',  []],
//            'frameeditor' => ['Relations Editor', '/visualeditor/frame/main', 'material wt-icon-frame', 'MASTER', []],
//            'corenesseditor' => ['Coreness Editor', '/visualeditor/frame/coreness', 'material wt-icon-fe', 'MASTER', []],
//        ]],
//        'cxn' => ['Constructions', '/cxn/main', 'wt-icon-cxn', '', [
//            'cxnreport' => ['Constructicons', '/report/cxn/main', 'material wt-icon-cxn', '',  []],
//            'ccngrapher' => ['CCN Grapher', '/grapher/ccn/main', 'material wt-icon-grapher', '',  []],
//            'cxneditor' => ['Relations Editor', '/visualeditor/cxn/main', 'material wt-icon-cxn', 'MASTER',  []],
//            'cxnframeeditor' => ['CxN-Frame Relation', '/visualeditor/cxnframe/main', 'material wt-icon-cxn', 'MASTER', []],
//        ]],
//        'lexicon' => ['Lexicon', '/report/lu/main', 'wt-icon-lexicon', '', [
//            'lureport' => ['LU', '/report/lu/main', 'material wt-icon-lu', '',  []],
//            'lemmas' => ['Lemmas', '/structure/lemma/main', 'material wt-icon-lemma', 'MASTER', []],
//            'qualia' => ['Qualia', '/structure/qualia/main', 'material wt-icon-qualia', 'MASTER', []],
//        ]],
//        'corpus' => ['Corpus', '/structure/corpus/main', 'wt-icon-corpus', 'MASTER', [
//            'corpusAnnotationReport' => ['Corpus Annotation', '/report/corpus/main', 'material wt-icon-corpus', '',[]],
//            'corpusstructure' => ['Corpus/Document', '/structure/corpus/main', 'material wt-icon-corpus', 'MASTER', []],
//        ]],
//        'corpusannotation' => ['Text Annotation', '/annotationCorpus/main', 'wt-icon-annotation', 'ANNO',  [
//        ]],
//        'staticannotation' => ['Static Annotation', '/annotationStatic/main', 'wt-icon-annotation-static', 'ANNO',  [
//        ]],
//        'dynamicannotation' => ['Dynamic Annotation', '/annotationDynamic/main', 'wt-icon-annotation-dynamic', 'ANNO',  [
//        ]],
//        'multimodal' => ['Multimodal', '/reportMultimodal/main', 'wt-icon-annotation-dynamic', '', [
//        ]],
//        'grapher' => ['Grapher', '/grapherFrame/main', 'wt-icon-frame', '', [
//            'frameGrapher' => ['Frame', '/grapherFrame/main', 'wt-icon-frame', '',[]],
//        ]],
            'report' => ['Report', '', '', '', [
                'multimodal' => ['Multimodal', '/reportMultimodal/main', 'wt-icon-annotation-dynamic', '', []],
            ]],

//        'utils' => ['Utils', '/utils/main', 'wt-icon-utils', 'MASTER',  [
//            'importLexWf' => ['Import Wf-Lexeme', '/utils/import/formImportLexWf', 'material wt-icon-util', 'MASTER', []],
//            'wflex' => ['Search Wf-Lexeme', '/admin/wflex/main', 'material wt-icon-util', 'MASTER', []],
//            'registerWfLex' => ['Register Wf-Lexeme', '/utils/register/formRegisterLexWf', 'material wt-icon-util', 'MASTER',  []],
//            'registerLemma' => ['Register Lemma', '/utils/register/formRegisterLemma', 'material wt-icon-util', 'MASTER',  []],
//            'exportCxnFS' => ['Export Cxn as FS', '/utils/export/formExportCxnFS', 'material wt-icon-util', 'ADMIN',  []],
//            'exportCxnJson' => ['Export Cxn', '/utils/export/formExportCxn', 'material wt-icon-util', 'ADMIN', []],
//        ]],
        ]],
        'user' => ['userPanel', '/admin/user/main', 'fnbrIconForm', '', [
            'language' => ['Language', '/main/language', 'wt-lang-en', '', [
                'en' => ['English', '/main/changeLanguage/en', 'wt-lang-en', '', []],
                'pt' => ['Português', '/main/changeLanguage/pt', 'wt-lang-pt', '', []],
            ]],
//        'profile' => ['Profile', '/main/profile', 'wt-icon-user', '',  [
//            'myprofile' => ['My Profile', '/profile/formMyProfile', 'wt-icon-profile', '',  []],
//            'logout' => ['Logout', '/logout', 'material wt-icon-logout', '', []],
//        ]],
        ]]
    ],
    'dashboard' => [
        'en' => [
            'subcorpus' => 'Subcorpus',
            'annotatorProfile' => 'Annotator profile',
            'textualAnnotation' => "Anotação de Texto",
            'videoAnnotation' => 'Anotação de Vídeo',
            'imageAnnotation' => 'Anotação de Imagem',
            'averages' => 'Médias',
            'ptt' => "PTT: Tradução para Português",
            'pto' => "PTO: Português Original",
            'eno' => "ENO: Inglês Original",
            'annotatedSentences' => 'Sentenças anotadas',
            'annotatedImages' => 'Imagens anotadas',
            'frames' => 'Frames distintos',
            'fe' => 'Elementos de Frame distintos',
            'lu' => 'Unidades Lexicais distintas',
            'bbox' => 'Bounding Boxes anotadas',
            'cv' => 'Tipos de Objetos distintos',
            'avgSentence' => 'Média por sentença',
            'avgSentenceUL' => 'Unidades Lexicais',
            'avgBBox' => 'Tempo médio por Boundin Box',
            'avgBBoxSeconds' => 'segundos'

        ],
        'pt' => [
            'subcorpus' => 'Subcorpus',
            'annotatorProfile' => 'Perfil do anotador'
        ],
    ]

];
