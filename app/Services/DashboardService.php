<?php

namespace App\Services;

use App\Database\Criteria;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Orkester\Persistence\PersistenceManager;

class DashboardService //extends AppService
{
    public static function subcorporaFrame2(): int
    {
        $count = CorpusModel::getCriteria()
            ->where('entry', '=', 'crp_pedro_pelo_mundo')
            ->get("count(documents.sentences.idSentence) as n");
        return $count[0]['n'];
    }

    public static function subcorporaAudition(): int
    {
        $count = CorpusModel::getCriteria()
            ->where('entry', '=', 'crp_curso_dataset')
            ->get("count(documents.sentences.idSentence) as n");
        return $count[0]['n'];
    }

    public static function subcorporaMulti30k(): int
    {
        $countCom = CorpusModel::getCriteria()
            ->where('entry', 'IN', [
                'crp_oficina_com_sentenca_1',
                'crp_oficina_com_sentenca_2',
                'crp_oficina_com_sentenca_3',
                'crp_oficina_com_sentenca_4',
                'crp_corpus-prime-com-sentença'
            ])
            ->get("count(documents.sentences.idSentence) as n");
        $countSem = CorpusModel::getCriteria()
            ->where('entry', 'IN', [
                'crp_oficina_sem_sentenca_1',
                'crp_oficina_sem_sentenca_2',
                'crp_oficina_sem_sentenca_3',
                'crp_oficina_sem_sentenca_4',
                'crp_corpus-prime-sem-sentença',
            ])
            ->get("count(documents.sentences.idSentence) as n");
        return $countCom[0]['n'] + $countSem[0]['n'];
    }

    private static function getSentences()
    {
        return SentenceModel::getCriteria()
            ->distinct()
            ->select("idSentence")
            ->where('documents.corpus.entry', 'IN', [
                'crp_pedro_pelo_mundo',
                'crp_curso_dataset',
                'crp_oficina_com_sentenca_1',
                'crp_oficina_com_sentenca_2',
                'crp_oficina_com_sentenca_3',
                'crp_oficina_com_sentenca_4',
                'crp_oficina_sem_sentenca_1',
                'crp_oficina_sem_sentenca_2',
                'crp_oficina_sem_sentenca_3',
                'crp_oficina_sem_sentenca_4',
                'crp_corpus-prime-sem-sentença',
                'crp_corpus-prime-com-sentença',
            ]);
    }

    public static function annoFulltext(): int
    {
        $sentences = self::getSentences();
        $count = AnnotationSetModel::getCriteria()
            ->where('sentence.idSentence', 'IN', $sentences)
            ->get("count(idAnnotationSet) as n");
        return $count[0]['n'];
    }

    public static function annoStatic(): int
    {
        $sentences = self::getSentences();
        $count = StaticObjectMMModel::getCriteria()
            ->where('sentenceMM.idSentence', 'IN', $sentences)
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->get("count(idObjectSentenceMM) as n");
        return $count[0]['n'];
    }

    public static function annoDynamic(): int
    {
        $count = DynamicObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo', 'crp_curso_dataset'])
            ->get("count(idObjectMM) as n");
        return $count[0]['n'];
    }

    public static function categoryFrame(): int
    {
        $sentences = self::getSentences();
        $frames = [];
        $frameDynamic = DynamicObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo', 'crp_curso_dataset'])
            ->get("frameElement.idFrame");
        foreach ($frameDynamic as $row) {
            $frames[$row['idFrame']] = 1;
        }
        $frameStatic = StaticObjectSentenceMMModel::getCriteria()
            ->where('sentenceMM.idSentence', 'IN', $sentences)
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->get("frameElement.idFrame");
        foreach ($frameStatic as $row) {
            $frames[$row['idFrame']] = 1;
        }
        $frameText = LabelModel::getCriteria()
            ->where('layer.annotationSet.sentence.idSentence', 'IN', $sentences)
            ->get("frameElement.idFrame");
        foreach ($frameText as $row) {
            $frames[$row['idFrame']] = 1;
        }
        return count($frames);
    }

    public static function categoryFE(): int
    {
        $sentences = self::getSentences();
        $fes = [];
        $feDynamic = DynamicObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo', 'crp_curso_dataset'])
            ->get("idFrameElement");
        foreach ($feDynamic as $row) {
            $fes[$row['idFrameElement']] = 1;
        }
        $feStatic = StaticObjectSentenceMMModel::getCriteria()
            ->where('sentenceMM.idSentence', 'IN', $sentences)
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->get("idFrameElement");
        foreach ($feStatic as $row) {
            $fes[$row['idFrameElement']] = 1;
        }
        $feText = LabelModel::getCriteria()
            ->where('layer.annotationSet.sentence.idSentence', 'IN', $sentences)
            ->get("frameElement.idFrameElement");
        foreach ($feText as $row) {
            $fes[$row['idFrameElement']] = 1;
        }
        return count($fes);
    }

    public static function categoryCV(): int
    {
        $sentences = self::getSentences();
        $lus = [];
        $luDynamic = DynamicObjectMMModel::getCriteria()
            ->where('idLU', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo', 'crp_curso_dataset'])
            ->get("idLU");
        foreach ($luDynamic as $row) {
            $lus[$row['idLU']] = 1;
        }
        $luStatic = StaticObjectSentenceMMModel::getCriteria()
            ->where('sentenceMM.idSentence', 'IN', $sentences)
            ->where('idLU', 'IS', 'NOT NULL')
            ->get("idLU");
        foreach ($luStatic as $row) {
            $lus[$row['idLU']] = 1;
        }
        $luText = AnnotationSetModel::getCriteria()
            ->where('sentence.idSentence', 'IN', $sentences)
            ->get("lu.idLU");
        foreach ($luText as $row) {
            $lus[$row['idLU']] = 1;
        }
        return count($lus);
    }

    //
    // Frame2
    //

    /*
    private static function getSentencesFrame2($query)
    {
        $query->from("view_document_sentence as ds")
            ->select("ds.idSentence")
            ->join("document as d","ds.idDocument","=","d.idDocument")
            ->join("corpus as c","d.idCorpus","=","c.idCorpus")
            ->where("c.entry","IN",[
                'crp_pedro_pelo_mundo'
            ]);
    }


    public static function frame2(): array
    {
        $result = [];
        $sentences = function ($query) {
            self::getSentencesFrame2($query);
        };
        $count = Criteria::table("annotationset")
            ->where('idSentence', 'IN', $sentences)
            ->selectRaw("count(distinct idSentence) as n")
            ->all();
        $result['sentences'] = $count[0]->n;
        $count = Criteria::table("view_dynamicobject_boundingbox as bb")
            ->join("view_video_dynamicobject as vd","bb.idDynamicObject","=","vd.idDynamicObject")
            ->join("view_document_video as dv","vd.idVideo","=","dv.idVideo")
            ->join("dynamicobject as do","vd.idDynamicObject","=","do.idDynamicObject")
            ->join("view_annotation as a","do.idAnnotationObject","=","a.idAnnotationObject")
            ->join("frameelement as fe","a.idEntity","=","fe.idEntity")
            ->join("document as d","dv.idDocument","=","d.idDocument")
            ->join("corpus as c","d.idCorpus","=","c.idCorpus")
            ->where('c.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->selectRaw("count(distinct bb.idBoundingBox) as n")
            ->all();
        $result['bbox'] = $count[0]->n;
        $count1 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a","afe.idAnnotationSet","=","a.idAnnotationSet")
            ->join("frameelement as fe","afe.idFrameElement","=","fe.idFrameElement")
            ->where('a.idSentence', 'IN', $sentences)
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $count2 = Criteria::table("view_dynamicobject_boundingbox as bb")
            ->join("view_video_dynamicobject as vd","bb.idDynamicObject","=","vd.idDynamicObject")
            ->join("view_document_video as dv","vd.idVideo","=","dv.idVideo")
            ->join("dynamicobject as do","vd.idDynamicObject","=","do.idDynamicObject")
            ->join("view_annotation as a","do.idAnnotationObject","=","a.idAnnotationObject")
            ->join("frameelement as fe","a.idEntity","=","fe.idEntity")
            ->join("document as d","dv.idDocument","=","d.idDocument")
            ->join("corpus as c","d.idCorpus","=","c.idCorpus")
            ->where('c.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $result['framesText'] = $count1[0]->n;
        $result['framesBBox'] = $count2[0]->n;
        $count1 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a","afe.idAnnotationSet","=","a.idAnnotationSet")
            ->where('a.idSentence', 'IN', $sentences)
            ->selectRaw("count(distinct afe.idFrameElement) as n")
            ->all();
        $count2 = Criteria::table("view_dynamicobject_boundingbox as bb")
            ->join("view_video_dynamicobject as vd","bb.idDynamicObject","=","vd.idDynamicObject")
            ->join("view_document_video as dv","vd.idVideo","=","dv.idVideo")
            ->join("dynamicobject as do","vd.idDynamicObject","=","do.idDynamicObject")
            ->join("view_annotation as a","do.idAnnotationObject","=","a.idAnnotationObject")
            ->join("frameelement as fe","a.idEntity","=","fe.idEntity")
            ->join("document as d","dv.idDocument","=","d.idDocument")
            ->join("corpus as c","d.idCorpus","=","c.idCorpus")
            ->where('c.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->selectRaw("count(distinct fe.idFrameElement) as n")
            ->all();
        $count3 = Criteria::table("annotationset")
            ->where('idSentence', 'IN', $sentences)
            ->selectRaw("count(*) as n")
            ->all();
        $result['fesText'] = $count1[0]->n;
        $result['fesBBox'] = $count2[0]->n;
        $result['asText'] = $count3[0]->n;
        $count1 = Criteria::table("annotationset")
            ->where('idSentence', 'IN', $sentences)
            ->selectRaw("count(distinct idLU) as n")
            ->all();
        $count2 = Criteria::table("view_dynamicobject_boundingbox as bb")
            ->join("view_video_dynamicobject as vd","bb.idDynamicObject","=","vd.idDynamicObject")
            ->join("view_document_video as dv","vd.idVideo","=","dv.idVideo")
            ->join("dynamicobject as do","vd.idDynamicObject","=","do.idDynamicObject")
            ->join("view_annotation as a","do.idAnnotationObject","=","a.idAnnotationObject")
            ->join("lu","a.idEntity","=","lu.idEntity")
            ->join("document as d","dv.idDocument","=","d.idDocument")
            ->join("corpus as c","d.idCorpus","=","c.idCorpus")
            ->where('c.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->selectRaw("count(distinct lu.idLU) as n")
            ->all();
        $result['lusText'] = $count1[0]->n;
        $result['lusBBox'] = $count2[0]->n;
        $counts = Criteria::table("annotationset")
            ->where('idSentence', 'IN', $sentences)
            ->selectRaw("count(idAnnotationSet) as a, count(distinct idSentence) as s")
            ->all();
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $result['avgAS']= number_format($counts[0]->a / $counts[0]->s, 3, $decimal, '');
        $count = Criteria::table("dynamicobject as do")
            ->join("view_video_dynamicobject as vd","vd.idDynamicObject","=","do.idDynamicObject")
            ->join("view_document_video as dv","vd.idVideo","=","dv.idVideo")
            ->join("document as d","dv.idDocument","=","d.idDocument")
            ->join("corpus as c","d.idCorpus","=","c.idCorpus")
            ->where('c.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->selectRaw("count(distinct do.idDynamicObject) as n")
            ->all();
        $sum = 0;
        foreach ($count as $row) {
            $sum += $row->n;
        }
        $avg = ($sum / count($count)) * 0.040; // 40 ms por frame
        $result['avgDuration'] = number_format($avg, 3, $decimal, '');
        return $result;
    }
    */


    public static function frame2(): array
    {
        $result = [];
        $corpora = [
            'crp_pedro_pelo_mundo',
            'crp_ppm_nlg',
            'crp_ppm_gesture'
        ];
        $count0 = Criteria::table("annotationset as a")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->selectRaw("count(distinct a.idSentence) as nSentence")
            ->selectRaw("count(*) as nAS")
            ->selectRaw("count(distinct a.idLU) as nLU")
            ->all();
        $result['sentences'] = $count0[0]->nSentence;
        $result['asText'] = $count0[0]->nAS;
        $result['lusText'] = $count0[0]->nLU;
        $count1 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct do.idDynamicObject) as n")
            ->all();
        $result['bbox'] = $count1[0]->n;
        $count2 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a", "afe.idAnnotationSet", "=", "a.idAnnotationSet")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->join("frameelement as fe", "afe.idFrameElement", "=", "fe.idFrameElement")
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $count3 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $result['framesText'] = $count2[0]->n;
        $result['framesBBox'] = $count3[0]->n;
        $count4 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a", "afe.idAnnotationSet", "=", "a.idAnnotationSet")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->selectRaw("count(distinct afe.idFrameElement) as n")
            ->all();
        $count5 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct fe.idFrameElement) as n")
            ->all();
        $result['fesText'] = $count4[0]->n;
        $result['fesBBox'] = $count5[0]->n;

        $count6 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("lu", "a.idEntity", "=", "lu.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct lu.idLU) as n")
            ->all();
        $result['lusBBox'] = $count6[0]->n;

        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $result['avgAS'] = number_format($result['asText'] / $result['sentences'], 3, $decimal, '');
        $count7 = Criteria::table("view_dynamicobject_boundingbox as bb")
            ->join("view_video_dynamicobject as vd", "bb.idDynamicObject", "=", "vd.idDynamicObject")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->groupBy("bb.idDynamicObject")
            ->selectRaw("count(*) as n")
            ->all();
        $sum = 0;
        foreach ($count7 as $row) {
            $sum += $row->n;
        }
        $avg = ($sum / count($count7)) * 0.040; // 40 ms por frame
        $result['avgDuration'] = number_format($avg, 3, $decimal, '');
        return $result;
    }


    public static function frame2PPM(): array
    {
        $result = [];
        $corpora = [
            'crp_pedro_pelo_mundo'
        ];
        $count0 = Criteria::table("annotationset as a")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->selectRaw("count(distinct a.idSentence) as nSentence")
            ->selectRaw("count(*) as nAS")
            ->selectRaw("count(distinct a.idLU) as nLU")
            ->all();
        $result['sentences'] = $count0[0]->nSentence;
        $result['asText'] = $count0[0]->nAS;
        $result['lusText'] = $count0[0]->nLU;
        $count1 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct do.idDynamicObject) as n")
            ->all();
        $result['bbox'] = $count1[0]->n;
        $count2 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a", "afe.idAnnotationSet", "=", "a.idAnnotationSet")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->join("frameelement as fe", "afe.idFrameElement", "=", "fe.idFrameElement")
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $count3 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $result['framesText'] = $count2[0]->n;
        $result['framesBBox'] = $count3[0]->n;
        $count4 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a", "afe.idAnnotationSet", "=", "a.idAnnotationSet")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->selectRaw("count(distinct afe.idFrameElement) as n")
            ->all();
        $count5 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct fe.idFrameElement) as n")
            ->all();
        $result['fesText'] = $count4[0]->n;
        $result['fesBBox'] = $count5[0]->n;

        $count6 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("lu", "a.idEntity", "=", "lu.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct lu.idLU) as n")
            ->all();
        $result['lusBBox'] = $count6[0]->n;

        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $result['avgAS'] = number_format($result['asText'] / $result['sentences'], 3, $decimal, '');
        $count7 = Criteria::table("view_dynamicobject_boundingbox as bb")
            ->join("view_video_dynamicobject as vd", "bb.idDynamicObject", "=", "vd.idDynamicObject")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->groupBy("bb.idDynamicObject")
            ->selectRaw("count(*) as n")
            ->all();
        $sum = 0;
        foreach ($count7 as $row) {
            $sum += $row->n;
        }
        $avg = ($sum / count($count7)) * 0.040; // 40 ms por frame
        $result['avgDuration'] = number_format($avg, 3, $decimal, '');
        return $result;
    }

    public static function frame2NLG(): array
    {
        $result = [];
        $corpora = [
            'crp_ppm_nlg'
        ];
        $count0 = Criteria::table("annotationset as a")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->selectRaw("count(distinct a.idSentence) as nSentence")
            ->selectRaw("count(*) as nAS")
            ->selectRaw("count(distinct a.idLU) as nLU")
            ->all();
        $result['sentences'] = $count0[0]->nSentence;
        $result['asText'] = $count0[0]->nAS;
        $result['lusText'] = $count0[0]->nLU;
        $count1 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct do.idDynamicObject) as n")
            ->all();
        $result['bbox'] = $count1[0]->n;
        $count2 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a", "afe.idAnnotationSet", "=", "a.idAnnotationSet")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->join("frameelement as fe", "afe.idFrameElement", "=", "fe.idFrameElement")
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $count3 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $result['framesText'] = $count2[0]->n;
        $result['framesBBox'] = $count3[0]->n;
        $count4 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a", "afe.idAnnotationSet", "=", "a.idAnnotationSet")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->selectRaw("count(distinct afe.idFrameElement) as n")
            ->all();
        $count5 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct fe.idFrameElement) as n")
            ->all();
        $result['fesText'] = $count4[0]->n;
        $result['fesBBox'] = $count5[0]->n;

        $count6 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("lu", "a.idEntity", "=", "lu.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct lu.idLU) as n")
            ->all();
        $result['lusBBox'] = $count6[0]->n;

        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $result['avgAS'] = ($result['sentences'] > 0) ? number_format($result['asText'] / $result['sentences'], 3, $decimal, '') : 0;
        $count7 = Criteria::table("view_dynamicobject_boundingbox as bb")
            ->join("view_video_dynamicobject as vd", "bb.idDynamicObject", "=", "vd.idDynamicObject")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->groupBy("bb.idDynamicObject")
            ->selectRaw("count(*) as n")
            ->all();
        $sum = 0;
        foreach ($count7 as $row) {
            $sum += $row->n;
        }
        $avg = ($sum / count($count7)) * 0.040; // 40 ms por frame
        $result['avgDuration'] = number_format($avg, 3, $decimal, '');
        return $result;
    }

    public static function frame2Gesture(): array
    {
        $result = [];
        $corpora = [
            'crp_ppm_gesture'
        ];
        $count0 = Criteria::table("annotationset as a")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->selectRaw("count(distinct a.idSentence) as nSentence")
            ->selectRaw("count(*) as nAS")
            ->selectRaw("count(distinct a.idLU) as nLU")
            ->all();
        $result['sentences'] = $count0[0]->nSentence;
        $result['asText'] = $count0[0]->nAS;
        $result['lusText'] = $count0[0]->nLU;
        $count1 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct do.idDynamicObject) as n")
            ->all();
        $result['bbox'] = $count1[0]->n;
        $count2 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a", "afe.idAnnotationSet", "=", "a.idAnnotationSet")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->join("frameelement as fe", "afe.idFrameElement", "=", "fe.idFrameElement")
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $count3 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $result['framesText'] = $count2[0]->n;
        $result['framesBBox'] = $count3[0]->n;
        $count4 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a", "afe.idAnnotationSet", "=", "a.idAnnotationSet")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->selectRaw("count(distinct afe.idFrameElement) as n")
            ->all();
        $count5 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct fe.idFrameElement) as n")
            ->all();
        $result['fesText'] = $count4[0]->n;
        $result['fesBBox'] = $count5[0]->n;

        $count6 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("lu", "a.idEntity", "=", "lu.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct lu.idLU) as n")
            ->all();
        $result['lusBBox'] = $count6[0]->n;

        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $result['avgAS'] = ($result['sentences'] > 0) ? number_format($result['asText'] / $result['sentences'], 3, $decimal, '') : 0;
        $count7 = Criteria::table("view_dynamicobject_boundingbox as bb")
            ->join("view_video_dynamicobject as vd", "bb.idDynamicObject", "=", "vd.idDynamicObject")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->groupBy("bb.idDynamicObject")
            ->selectRaw("count(*) as n")
            ->all();
        $sum = 0;
        foreach ($count7 as $row) {
            $sum += $row->n;
        }
        $avg = ($sum / count($count7)) * 0.040; // 40 ms por frame
        $result['avgDuration'] = number_format($avg, 3, $decimal, '');
        return $result;
    }
    //
    // Audition
    //

    /*
    private static function getSentencesAudition()
    {
        return SentenceModel::getCriteria()
            ->distinct()
            ->select("idSentence")
            ->where('documents.corpus.entry', 'IN', [
                'crp_curso_dataset',
                'crp_hoje_eu_nao_quero'
            ]);
    }

    public static function audition(): array
    {
        $result = [];
        $sentences = self::getSentencesAudition();
        $count = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct idSentence) as n");
        $result['sentences'] = $count[0]['n'];
        $count = DynamicObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('document.corpus.entry', 'IN', ['crp_curso_dataset', 'crp_hoje_eu_nao_quero'])
            ->get("count(distinct idDynamicObjectMM) as n");
        $result['bbox'] = $count[0]['n'];
        $count1 = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct lu.idFrame) as n");
        $count2 = DynamicObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('document.corpus.entry', 'IN', ['crp_curso_dataset', 'crp_hoje_eu_nao_quero'])
            ->get("count(distinct frameElement.idFrame) as n");
        $result['framesText'] = $count1[0]['n'];
        $result['framesBBox'] = $count2[0]['n'];
        $count1 = LabelModel::getCriteria()
            ->where('layer.annotationSet.sentence.idSentence', 'IN', $sentences)
            ->get("count(distinct frameElement.idFrameElement) as n");
        $count2 = DynamicObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('document.corpus.entry', 'IN', ['crp_curso_dataset', 'crp_hoje_eu_nao_quero'])
            ->get("count(distinct idFrameElement) as n");
        $result['fesText'] = $count1[0]['n'];
        $result['fesBBox'] = $count2[0]['n'];
        $count1 = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct lu.idLU) as n");
        $count2 = DynamicObjectMMModel::getCriteria()
            ->where('idLU', 'IS', 'NOT NULL')
            ->where('document.corpus.entry', 'IN', ['crp_curso_dataset', 'crp_hoje_eu_nao_quero'])
            ->get("count(distinct idLU) as n");
        $count3 = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(*) as n");
        $result['lusText'] = $count1[0]['n'];
        $result['lusBBox'] = $count2[0]['n'];
        $result['asText'] = $count3[0]['n'];
        $counts = AnnotationSetModel::getCriteria()
            ->where('sentence.idSentence', 'IN', $sentences)
            ->get(["count(idAnnotationSet) as a", "count(distinct idSentence) as s"]);
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $result['avgAS'] = number_format($counts[0]['a'] / $counts[0]['s'], 3, $decimal, '');
        $count = DynamicBBoxMMModel::getCriteria()
            ->where('dynamicObjectMM.document.corpus.entry', 'IN', ['crp_curso_dataset', 'crp_hoje_eu_nao_quero'])
            ->groupBy("idDynamicObjectMM")
            ->get("count(*) as n");
        $sum = 0;
        foreach ($count as $row) {
            $sum += $row['n'];
        }
        $avg = ($sum / count($count)) * 0.040; // 40 ms por frame
        $result['avgDuration'] = number_format($avg, 3, $decimal, '');
        return $result;
    }
    */

    public static function audition(): array
    {
        $result = [];
        $corpora = [
            'crp_curso_dataset', // audition
            'crp_hoje_eu_nao_quero', // Curta-metragem_ENQVS
            'crp_ad alternativa curta_hoje_eu_não_quero', //Audiodescrição_alternativa_ENQVS
        ];
        $count0 = Criteria::table("annotationset as a")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->selectRaw("count(distinct a.idSentence) as nSentence")
            ->selectRaw("count(*) as nAS")
            ->selectRaw("count(distinct a.idLU) as nLU")
            ->all();
        $result['sentences'] = $count0[0]->nSentence;
        $result['asText'] = $count0[0]->nAS;
        $result['lusText'] = $count0[0]->nLU;
        $count1 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct do.idDynamicObject) as n")
            ->all();
        $result['bbox'] = $count1[0]->n;
        $count2 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a", "afe.idAnnotationSet", "=", "a.idAnnotationSet")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->join("frameelement as fe", "afe.idFrameElement", "=", "fe.idFrameElement")
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $count3 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct fe.idFrame) as n")
            ->all();
        $result['framesText'] = $count2[0]->n;
        $result['framesBBox'] = $count3[0]->n;
        $count4 = Criteria::table("view_annotation_text_fe as afe")
            ->join("annotationset as a", "afe.idAnnotationSet", "=", "a.idAnnotationSet")
            ->join("view_document_sentence as ds", "a.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->selectRaw("count(distinct afe.idFrameElement) as n")
            ->all();
        $count5 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("frameelement as fe", "a.idEntity", "=", "fe.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct fe.idFrameElement) as n")
            ->all();
        $result['fesText'] = $count4[0]->n;
        $result['fesBBox'] = $count5[0]->n;

        $count6 = Criteria::table("view_video_dynamicobject as vd")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("dynamicobject as do", "vd.idDynamicObject", "=", "do.idDynamicObject")
            ->join("view_annotation as a", "do.idAnnotationObject", "=", "a.idAnnotationObject")
            ->join("lu", "a.idEntity", "=", "lu.idEntity")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->selectRaw("count(distinct lu.idLU) as n")
            ->all();
        $result['lusBBox'] = $count6[0]->n;

        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $result['avgAS'] = number_format($result['asText'] / $result['sentences'], 3, $decimal, '');
        $count7 = Criteria::table("view_dynamicobject_boundingbox as bb")
            ->join("view_video_dynamicobject as vd", "bb.idDynamicObject", "=", "vd.idDynamicObject")
            ->join("view_document_video as dv", "vd.idVideo", "=", "dv.idVideo")
            ->join("document as d", "dv.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->groupBy("bb.idDynamicObject")
            ->selectRaw("count(*) as n")
            ->all();
        $sum = 0;
        foreach ($count7 as $row) {
            $sum += $row->n;
        }
        $avg = ($sum / count($count7)) * 0.040; // 40 ms por frame
        $result['avgDuration'] = number_format($avg, 3, $decimal, '');

        return $result;
    }

    public static function auditionOrigin(): array
    {
        $corpora = [
            'crp_curso_dataset', // audition
            'crp_hoje_eu_nao_quero', // Curta-metragem_ENQVS
            'crp_ad alternativa curta_hoje_eu_não_quero', //Audiodescrição_alternativa_ENQVS
        ];
        $count8 = Criteria::table("sentence as s")
            ->join("originmm as o", "s.idOriginMM", "=", "o.idOriginMM")
            ->join("view_document_sentence as ds", "s.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where("c.entry", "IN", $corpora)
            ->groupBy("o.origin")
            ->selectRaw("o.origin")
            ->selectRaw("count(*) as nOrigin")
            ->all();

        return $count8;
    }

    //
    // Multi30k
    //

    public static function multi30k(): array
    {
        $result = [];
        $corpora = [
            'crp_oficina_com_sentenca_1',
            'crp_oficina_com_sentenca_2',
            'crp_oficina_com_sentenca_3',
            'crp_oficina_com_sentenca_4',
            'crp_oficina_sem_sentenca_1',
            'crp_oficina_sem_sentenca_2',
            'crp_oficina_sem_sentenca_3',
            'crp_oficina_sem_sentenca_4',
            'crp_corpus-prime-sem-sentença',
            'crp_corpus-prime-com-sentença',
        ];
        Criteria::$database = 'webtool37';
        $count = Criteria::table("staticannotationmm as sa")
            ->join("frameelement as fe", "sa.idFrameElement", "=", "fe.idFrameElement")
            ->join("staticobjectsentencemm as sos", "sa.idStaticObjectSentenceMM", "=", "sos.idStaticObjectSentenceMM")
            ->join("staticsentencemm as ss", "sos.idStaticSentenceMM", "=", "ss.idStaticSentenceMM")
            ->join("document_sentence as ds", "ss.idDocument", "=", "ds.idDocument")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->whereNotNull('sa.idFrameElement')
            ->selectRaw("count(distinct ss.idStaticSentenceMM) as n1")
            ->selectRaw("count(distinct sos.idStaticObjectSentenceMM) as n2")
            ->selectRaw("count(distinct fe.idFrame) as n3")
            ->selectRaw("count(distinct fe.idFrameElement) as n4")
            ->all();
        $result['images'] = $count[0]->n1;
        $result['bbox'] = $count[0]->n2;
        $result['framesImage'] = $count[0]->n3;
        $result['fesImage'] = $count[0]->n4;
        $result['lusImage'] = 0;

        $dbDaisy = DB::connection('daisy');
        // PTT
        $cmd = "select count(*) as n from flickr30ksentence where idDocumentFNBr = 1054 ";
        $count = $dbDaisy->select($cmd, []);
        $result['pttSentences'] = $count[0]->n;
        $cmd = "select count(distinct l.frame) as n
from lomeresult l
join flickr30ksentence f on (l.idFlickr30KSentence = f.idFlickr30KSentence)
where f.idDocumentFNBr = 1054";
        $count = $dbDaisy->select($cmd, []);
        $result['pttFrames'] = $count[0]->n;
        // PTO
        $cmd = "select count(*) as n from flickr30ksentence where idDocumentFNBr = 1055 ";
        $count = $dbDaisy->select($cmd, []);
        $result['ptoSentences'] = $count[0]->n;
        $cmd = "select count(distinct l.frame) as n
from lomeresult l
join flickr30ksentence f on (l.idFlickr30KSentence = f.idFlickr30KSentence)
where f.idDocumentFNBr = 1055";
        $count = $dbDaisy->select($cmd, []);
        $result['ptoFrames'] = $count[0]->n;
        // ENO
        $cmd = "select count(*) as n from flickr30ksentence where idDocumentFNBr = 663 ";
        $count = $dbDaisy->select($cmd, []);
        $result['enoSentences'] = $count[0]->n;
        $cmd = "select count(distinct l.frame) as n
from lomeresult l
join flickr30ksentence f on (l.idFlickr30KSentence = f.idFlickr30KSentence)
where f.idDocumentFNBr = 663";
        $count = $dbDaisy->select($cmd, []);
        $result['enoFrames'] = $count[0]->n;
        $result['chart'] = self::multi30kChart();
        return $result;
    }

    public static function multi30kEntity(): array
    {
        $result = [];
        $corpora = [
            'crp_oficina_com_sentenca_1',
            'crp_oficina_com_sentenca_2',
            'crp_oficina_com_sentenca_3',
            'crp_oficina_com_sentenca_4',
            'crp_oficina_sem_sentenca_1',
            'crp_oficina_sem_sentenca_2',
            'crp_oficina_sem_sentenca_3',
            'crp_oficina_sem_sentenca_4',
        ];
        Criteria::$database = 'webtool37';
        $count = Criteria::table("staticannotationmm as sa")
            ->join("frameelement as fe", "sa.idFrameElement", "=", "fe.idFrameElement")
            ->join("staticobjectsentencemm as sos", "sa.idStaticObjectSentenceMM", "=", "sos.idStaticObjectSentenceMM")
            ->join("staticsentencemm as ss", "sos.idStaticSentenceMM", "=", "ss.idStaticSentenceMM")
            ->join("document_sentence as ds", "ss.idDocument", "=", "ds.idDocument")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->whereNotNull('sa.idFrameElement')
            ->selectRaw("count(distinct ss.idStaticSentenceMM) as n1")
            ->selectRaw("count(distinct sos.idStaticObjectSentenceMM) as n2")
            ->selectRaw("count(distinct fe.idFrame) as n3")
            ->selectRaw("count(distinct fe.idFrameElement) as n4")
            ->all();
        $result['images'] = $count[0]->n1;
        $result['bbox'] = $count[0]->n2;
        $result['framesImage'] = $count[0]->n3;
        $result['fesImage'] = $count[0]->n4;
        $result['lusImage'] = 0;

        return $result;
    }
    public static function multi30kEvent(): array
    {
        $result = [];
        $corpora = [
            'crp_corpus-prime-sem-sentença',
            'crp_corpus-prime-com-sentença',
        ];
        Criteria::$database = 'webtool37';
        $count = Criteria::table("staticannotationmm as sa")
            ->join("frameelement as fe", "sa.idFrameElement", "=", "fe.idFrameElement")
            ->join("staticobjectsentencemm as sos", "sa.idStaticObjectSentenceMM", "=", "sos.idStaticObjectSentenceMM")
            ->join("staticsentencemm as ss", "sos.idStaticSentenceMM", "=", "ss.idStaticSentenceMM")
            ->join("document_sentence as ds", "ss.idDocument", "=", "ds.idDocument")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("corpus as c", "d.idCorpus", "=", "c.idCorpus")
            ->where('c.entry', 'IN', $corpora)
            ->whereNotNull('sa.idFrameElement')
            ->selectRaw("count(distinct ss.idStaticSentenceMM) as n1")
            ->selectRaw("count(distinct sos.idStaticObjectSentenceMM) as n2")
            ->selectRaw("count(distinct fe.idFrame) as n3")
            ->selectRaw("count(distinct fe.idFrameElement) as n4")
            ->all();
        $result['images'] = $count[0]->n1;
        $result['bbox'] = $count[0]->n2;
        $result['framesImage'] = $count[0]->n3;
        $result['fesImage'] = $count[0]->n4;
        $result['lusImage'] = 0;

        return $result;
    }
    public static function multi30kChart(): array
    {
        $dbFnbr = DB::connection('webtool37');
        $cmd = "SELECT year(tlDateTime) y, month(tlDateTime) m, count(*) n
         FROM fnbr_db.timeline t
where (tablename='objectsentencemm') or (tablename='staticannotationmm')
group by year(tlDateTime),month(tlDateTime)
order by 1,2;";
        $rows = $dbFnbr->select($cmd, []);
        $chart = [];
        $sum = 0;
        foreach ($rows as $row) {
            $sum += is_object($row) ? $row->n : $row['n'];
            $m = is_object($row) ? $row->m : $row['m'];
            $y = is_object($row) ? $row->y : $row['y'];
            $chart[] = [
                'm' => $m . '/' . $y,
                'value' => $sum
            ];
        }
        return $chart;
    }

    public static function updateTable($data)
    {
        $dbDaisy = DB::connection('daisy');
        $now = date('Y-m-d H:i:s');
        $frame2_avg_sentence = str_replace(',', '.', $data->frame2['avgDuration']);
        $frame2_avg_obj = str_replace(',', '.', $data->frame2['avgAS']);
        $frame2PPM_avg_sentence = str_replace(',', '.', $data->frame2PPM['avgDuration']);
        $frame2PPM_avg_obj = str_replace(',', '.', $data->frame2PPM['avgAS']);
        $frame2NLG_avg_sentence = str_replace(',', '.', $data->frame2NLG['avgDuration']);
        $frame2NLG_avg_obj = str_replace(',', '.', $data->frame2NLG['avgAS']);
        $frame2Gesture_avg_sentence = str_replace(',', '.', $data->frame2Gesture['avgDuration']);
        $frame2Gesture_avg_obj = str_replace(',', '.', $data->frame2Gesture['avgAS']);
        $audition_avg_sentence = str_replace(',', '.', $data->audition['avgDuration']);
        $audition_avg_obj = str_replace(',', '.', $data->audition['avgAS']);
        $cmd = "update dashboard set
 timeLastUpdate = '{$now}',
 frame2_text_sentence = {$data->frame2['sentences']},
 frame2_text_frame = {$data->frame2['framesText']},
 frame2_text_ef = {$data->frame2['fesText']},
 frame2_text_lu = {$data->frame2['lusText']},
 frame2_text_as = {$data->frame2['asText']},
 frame2_video_bbox = {$data->frame2['bbox']},
 frame2_video_frame = {$data->frame2['framesBBox']},
 frame2_video_ef = {$data->frame2['fesBBox']},
 frame2_video_obj = {$data->frame2['lusBBox']},
 frame2_avg_sentence = {$frame2_avg_sentence},
 frame2_avg_obj = {$frame2_avg_obj},
  frame2PPM_text_sentence = {$data->frame2PPM['sentences']},
 frame2PPM_text_frame = {$data->frame2PPM['framesText']},
 frame2PPM_text_ef = {$data->frame2PPM['fesText']},
 frame2PPM_text_lu = {$data->frame2PPM['lusText']},
 frame2PPM_text_as = {$data->frame2PPM['asText']},
 frame2PPM_video_bbox = {$data->frame2PPM['bbox']},
 frame2PPM_video_frame = {$data->frame2PPM['framesBBox']},
 frame2PPM_video_ef = {$data->frame2PPM['fesBBox']},
 frame2PPM_video_obj = {$data->frame2PPM['lusBBox']},
 frame2PPM_avg_sentence = {$frame2PPM_avg_sentence},
 frame2PPM_avg_obj = {$frame2PPM_avg_obj},
  frame2NLG_text_sentence = {$data->frame2NLG['sentences']},
 frame2NLG_text_frame = {$data->frame2NLG['framesText']},
 frame2NLG_text_ef = {$data->frame2NLG['fesText']},
 frame2NLG_text_lu = {$data->frame2NLG['lusText']},
 frame2NLG_text_as = {$data->frame2NLG['asText']},
 frame2NLG_video_bbox = {$data->frame2NLG['bbox']},
 frame2NLG_video_frame = {$data->frame2NLG['framesBBox']},
 frame2NLG_video_ef = {$data->frame2NLG['fesBBox']},
 frame2NLG_video_obj = {$data->frame2NLG['lusBBox']},
 frame2NLG_avg_sentence = {$frame2NLG_avg_sentence},
 frame2NLG_avg_obj = {$frame2NLG_avg_obj},
  frame2Gesture_text_sentence = {$data->frame2Gesture['sentences']},
 frame2Gesture_text_frame = {$data->frame2Gesture['framesText']},
 frame2Gesture_text_ef = {$data->frame2Gesture['fesText']},
 frame2Gesture_text_lu = {$data->frame2Gesture['lusText']},
 frame2Gesture_text_as = {$data->frame2Gesture['asText']},
 frame2Gesture_video_bbox = {$data->frame2Gesture['bbox']},
 frame2Gesture_video_frame = {$data->frame2Gesture['framesBBox']},
 frame2Gesture_video_ef = {$data->frame2Gesture['fesBBox']},
 frame2Gesture_video_obj = {$data->frame2Gesture['lusBBox']},
 frame2Gesture_avg_sentence = {$frame2Gesture_avg_sentence},
 frame2Gesture_avg_obj = {$frame2Gesture_avg_obj},
 audition_text_sentence = {$data->audition['sentences']},
 audition_text_frame = {$data->audition['framesText']},
 audition_text_ef = {$data->audition['fesText']},
 audition_text_lu = {$data->audition['lusText']},
 audition_text_as = {$data->audition['asText']},
 audition_video_bbox = {$data->audition['bbox']},
 audition_video_frame = {$data->audition['framesBBox']},
 audition_video_ef = {$data->audition['fesBBox']},
 audition_video_obj = {$data->audition['lusBBox']},
 audition_avg_sentence = {$audition_avg_sentence},
 audition_avg_obj = {$audition_avg_obj},
 multi30k_image_image = {$data->multi30k['images']},
 multi30k_image_bbox = {$data->multi30k['bbox']},
 multi30k_image_frame = {$data->multi30k['framesImage']},
 multi30k_image_ef = {$data->multi30k['fesImage']},
 multi30k_ptt_sentence = {$data->multi30k['pttSentences']},
 multi30k_ptt_lome = {$data->multi30k['pttFrames']},
 multi30k_pto_sentence = {$data->multi30k['ptoSentences']},
 multi30k_pto_lome = {$data->multi30k['ptoFrames']},
 multi30k_eno_sentence = {$data->multi30k['enoSentences']},
 multi30k_eno_lome = {$data->multi30k['enoFrames']},
 multi30kevent_image_image = {$data->multi30kEvent['images']},
 multi30kevent_image_bbox = {$data->multi30kEvent['bbox']},
 multi30kevent_image_frame = {$data->multi30kEvent['framesImage']},
 multi30kevent_image_ef = {$data->multi30kEvent['fesImage']},
 multi30kentity_image_image = {$data->multi30kEntity['images']},
 multi30kentity_image_bbox = {$data->multi30kEntity['bbox']},
 multi30kentity_image_frame = {$data->multi30kEntity['framesImage']},
 multi30kentity_image_ef = {$data->multi30kEntity['fesImage']}

 where idDashboard = 1
";
        $dbDaisy->update($cmd);
    }

    public static function mustCalculate(): bool
    {
        $dbFnbr = DB::connection('webtool37');
        $rows = $dbFnbr->select("SELECT max(tlDateTime) as lastAnnotationTime
         FROM fnbr_db.timeline t
where (tablename='objectsentencemm') or (tablename='staticannotationmm')");
        $lastAnnotationTime37 = is_object($rows[0]) ? $rows[0]->lastAnnotationTime : $rows[0]['lastAnnotationTime'];

        $dbWebtool4 = DB::connection('webtool');
        $rows = $dbWebtool4->select("
SELECT max(tl.tlDateTime) as lastAnnotationTime
from timeline tl
join dynamicobject dob on (tl.id = dob.idDynamicObject)
join annotation a on (dob.idAnnotationObject = a.idAnnotationObject)
join usertask_document utd on (a.idUserTask = utd.idUserTask)
join document d on (utd.idDocument = d.idDocument)
join corpus c on (d.idCorpus = c.idCorpus)
where (tableName='dynamicobject')
and (c.entry in (
    'crp_pedro_pelo_mundo',
    'crp_ppm_nlg',
    'crp_ppm_gesture',
    'crp_curso_dataset',
    'crp_hoje_eu_nao_quero',
    'crp_ad alternativa curta_hoje_eu_não_quero'
))
");
        $lastAnnotationTime40 = is_object($rows[0]) ? $rows[0]->lastAnnotationTime : $rows[0]['lastAnnotationTime'];

        $lastAnnotationTime = $lastAnnotationTime37;
        if ($lastAnnotationTime40 > $lastAnnotationTime) {
            $lastAnnotationTime = $lastAnnotationTime40;
        }


        $dbDaisy = DB::connection('daisy');
        $rows = $dbDaisy->select("SELECT max(timeLastUpdate) as lastUpdateTime
         FROM dashboard
");
        $lastUpdateTime = is_object($rows[0]) ? $rows[0]->lastUpdateTime : $rows[0]['lastUpdateTime'];

        return $lastAnnotationTime > $lastUpdateTime;
    }

    public static function getFromTable($data)
    {
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $dbDaisy = DB::connection('daisy');
        $cmd = "SELECT * FROM dashboard where (idDashBoard = 1)";
        $rows = $dbDaisy->select($cmd, []);
        $fields = $rows[0];
        $data->frame2['sentences'] = $fields->frame2_text_sentence;
        $data->frame2['framesText'] = $fields->frame2_text_frame;
        $data->frame2['fesText'] = $fields->frame2_text_ef;
        $data->frame2['lusText'] = $fields->frame2_text_lu;
        $data->frame2['asText'] = $fields->frame2_text_as;
        $data->frame2['bbox'] = $fields->frame2_video_bbox;
        $data->frame2['framesBBox'] = $fields->frame2_video_frame;
        $data->frame2['fesBBox'] = $fields->frame2_video_ef;
        $data->frame2['lusBBox'] = $fields->frame2_video_obj;
        $data->frame2['avgDuration'] = number_format($fields->frame2_avg_sentence, 3, $decimal, '');
        $data->frame2['avgAS'] = number_format($fields->frame2_avg_obj, 3, $decimal, '');
        $data->frame2PPM['sentences'] = $fields->frame2ppm_text_sentence;
        $data->frame2PPM['framesText'] = $fields->frame2ppm_text_frame;
        $data->frame2PPM['fesText'] = $fields->frame2ppm_text_ef;
        $data->frame2PPM['lusText'] = $fields->frame2ppm_text_lu;
        $data->frame2PPM['asText'] = $fields->frame2ppm_text_as;
        $data->frame2PPM['bbox'] = $fields->frame2ppm_video_bbox;
        $data->frame2PPM['framesBBox'] = $fields->frame2ppm_video_frame;
        $data->frame2PPM['fesBBox'] = $fields->frame2ppm_video_ef;
        $data->frame2PPM['lusBBox'] = $fields->frame2ppm_video_obj;
        $data->frame2PPM['avgDuration'] = number_format($fields->frame2ppm_avg_sentence, 3, $decimal, '');
        $data->frame2PPM['avgAS'] = number_format($fields->frame2ppm_avg_obj, 3, $decimal, '');
        $data->frame2NLG['sentences'] = $fields->frame2nlg_text_sentence;
        $data->frame2NLG['framesText'] = $fields->frame2nlg_text_frame;
        $data->frame2NLG['fesText'] = $fields->frame2nlg_text_ef;
        $data->frame2NLG['lusText'] = $fields->frame2nlg_text_lu;
        $data->frame2NLG['asText'] = $fields->frame2nlg_text_as;
        $data->frame2NLG['bbox'] = $fields->frame2nlg_video_bbox;
        $data->frame2NLG['framesBBox'] = $fields->frame2nlg_video_frame;
        $data->frame2NLG['fesBBox'] = $fields->frame2nlg_video_ef;
        $data->frame2NLG['lusBBox'] = $fields->frame2nlg_video_obj;
        $data->frame2NLG['avgDuration'] = number_format($fields->frame2nlg_avg_sentence, 3, $decimal, '');
        $data->frame2NLG['avgAS'] = number_format($fields->frame2nlg_avg_obj, 3, $decimal, '');
        $data->frame2Gesture['sentences'] = $fields->frame2gesture_text_sentence;
        $data->frame2Gesture['framesText'] = $fields->frame2gesture_text_frame;
        $data->frame2Gesture['fesText'] = $fields->frame2gesture_text_ef;
        $data->frame2Gesture['lusText'] = $fields->frame2gesture_text_lu;
        $data->frame2Gesture['asText'] = $fields->frame2gesture_text_as;
        $data->frame2Gesture['bbox'] = $fields->frame2gesture_video_bbox;
        $data->frame2Gesture['framesBBox'] = $fields->frame2gesture_video_frame;
        $data->frame2Gesture['fesBBox'] = $fields->frame2gesture_video_ef;
        $data->frame2Gesture['lusBBox'] = $fields->frame2gesture_video_obj;
        $data->frame2Gesture['avgDuration'] = number_format($fields->frame2gesture_avg_sentence, 3, $decimal, '');
        $data->frame2Gesture['avgAS'] = number_format($fields->frame2gesture_avg_obj, 3, $decimal, '');
        $data->audition['sentences'] = $fields->audition_text_sentence;
        $data->audition['framesText'] = $fields->audition_text_frame;
        $data->audition['fesText'] = $fields->audition_text_ef;
        $data->audition['lusText'] = $fields->audition_text_lu;
        $data->audition['asText'] = $fields->audition_text_as;
        $data->audition['bbox'] = $fields->audition_video_bbox;
        $data->audition['framesBBox'] = $fields->audition_video_frame;
        $data->audition['fesBBox'] = $fields->audition_video_ef;
        $data->audition['lusBBox'] = $fields->audition_video_obj;
        $data->audition['avgDuration'] = number_format($fields->audition_avg_sentence, 3, $decimal, '');
        $data->audition['avgAS'] = number_format($fields->audition_avg_obj, 3, $decimal, '');
        $data->multi30k['images'] = $fields->multi30k_image_image;
        $data->multi30k['bbox'] = $fields->multi30k_image_bbox;
        $data->multi30k['framesImage'] = $fields->multi30k_image_frame;
        $data->multi30k['fesImage'] = $fields->multi30k_image_ef;
        $data->multi30k['pttSentences'] = $fields->multi30k_ptt_sentence;
        $data->multi30k['pttFrames'] = $fields->multi30k_ptt_lome;
        $data->multi30k['ptoSentences'] = $fields->multi30k_pto_sentence;
        $data->multi30k['ptoFrames'] = $fields->multi30k_pto_lome;
        $data->multi30k['enoSentences'] = $fields->multi30k_eno_sentence;
        $data->multi30k['enoFrames'] = $fields->multi30k_eno_lome;
        $data->multi30kEntity['images'] = $fields->multi30kentity_image_image;
        $data->multi30kEntity['bbox'] = $fields->multi30kentity_image_bbox;
        $data->multi30kEntity['framesImage'] = $fields->multi30kentity_image_frame;
        $data->multi30kEntity['fesImage'] = $fields->multi30kentity_image_ef;
        $data->multi30kEvent['images'] = $fields->multi30kevent_image_image;
        $data->multi30kEvent['bbox'] = $fields->multi30kevent_image_bbox;
        $data->multi30kEvent['framesImage'] = $fields->multi30kevent_image_frame;
        $data->multi30kEvent['fesImage'] = $fields->multi30kevent_image_ef;
    }

}
