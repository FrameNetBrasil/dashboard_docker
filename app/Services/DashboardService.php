<?php

namespace App\Services;

use App\Models\AnnotationSetModel;
use App\Models\CorpusModel;
use App\Models\DocumentModel;
use App\Models\LabelModel;
use App\Models\ObjectFrameMMModel;
use App\Models\ObjectMMModel;
use App\Models\ObjectSentenceMMModel;
use App\Models\SentenceModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\App;
use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Enum\Join;
use Orkester\Persistence\PersistenceManager;
use PHPSQLParser\builders\IndexTypeBuilder;

class DashboardService extends AppService
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
            ])
            ->get("count(documents.sentences.idSentence) as n");
        $countSem = CorpusModel::getCriteria()
            ->where('entry', 'IN', [
                'crp_oficina_sem_sentenca_1',
                'crp_oficina_sem_sentenca_2',
                'crp_oficina_sem_sentenca_3',
                'crp_oficina_sem_sentenca_4',
            ])
            ->get("count(documents.sentences.idSentence) as n");
        return $countCom[0]['n'] + $countSem[0]['n'];
    }

    private static function getSentences($query)
    {
        $query->setModel(SentenceModel::class)
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
            ]);
    }

    public static function annoFulltext(): int
    {
        $sentences = function ($query) {
            self::getSentences($query);
        };
        $count = AnnotationSetModel::getCriteria()
            ->where('sentence.idSentence', 'IN', $sentences)
            ->get("count(idAnnotationSet) as n");
        return $count[0]['n'];
    }

    public static function annoStatic(): int
    {
        $sentences = function ($query) {
            self::getSentences($query);
        };
        $count = ObjectSentenceMMModel::getCriteria()
            ->where('sentenceMM.idSentence', 'IN', $sentences)
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->get("count(idObjectSentenceMM) as n");
        return $count[0]['n'];
    }

    public static function annoDynamic(): int
    {
        $count = ObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo', 'crp_curso_dataset'])
            ->get("count(idObjectMM) as n");
        return $count[0]['n'];
    }

    public static function categoryFrame(): int
    {
        $sentences = function ($query) {
            self::getSentences($query);
        };
        $frames = [];
        $frameDynamic = ObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo', 'crp_curso_dataset'])
            ->get("frameElement.idFrame");
        foreach ($frameDynamic as $row) {
            $frames[$row['idFrame']] = 1;
        }
        $frameStatic = ObjectSentenceMMModel::getCriteria()
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
        $sentences = function ($query) {
            self::getSentences($query);
        };
        $fes = [];
        $feDynamic = ObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo', 'crp_curso_dataset'])
            ->get("idFrameElement");
        foreach ($feDynamic as $row) {
            $fes[$row['idFrameElement']] = 1;
        }
        $feStatic = ObjectSentenceMMModel::getCriteria()
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
        $sentences = function ($query) {
            self::getSentences($query);
        };
        $lus = [];
        $luDynamic = ObjectMMModel::getCriteria()
            ->where('idLU', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo', 'crp_curso_dataset'])
            ->get("idLU");
        foreach ($luDynamic as $row) {
            $lus[$row['idLU']] = 1;
        }
        $luStatic = ObjectSentenceMMModel::getCriteria()
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

    private static function getSentencesFrame2($query)
    {
        $query->setModel(SentenceModel::class)
            ->distinct()
            ->select("idSentence")
            ->where('documents.corpus.entry', 'IN', [
                'crp_pedro_pelo_mundo'
            ]);

    }

    public static function frame2(): array
    {
        $result = [];
        $sentences = function ($query) {
            self::getSentencesFrame2($query);
        };
        $count = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct idSentence) as n");
        $result['sentences'] = $count[0]['n'];
        $count = ObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->get("count(distinct idObjectMM) as n");
        $result['bbox'] = $count[0]['n'];
        $count1 = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct lu.idFrame) as n");
        $count2 = ObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->get("count(distinct frameElement.idFrame) as n");
        $result['framesText'] = $count1[0]['n'];
        $result['framesBBox'] = $count2[0]['n'];
        $count1 = LabelModel::getCriteria()
            ->where('layer.annotationSet.sentence.idSentence', 'IN', $sentences)
            ->get("count(distinct frameElement.idFrameElement) as n");
        $count2 = ObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->get("count(distinct idFrameElement) as n");
        $result['fesText'] = $count1[0]['n'];
        $result['fesBBox'] = $count2[0]['n'];
        $count1 = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct lu.idLU) as n");
        $count2 = ObjectMMModel::getCriteria()
            ->where('idLU', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->get("count(distinct idLU) as n");
        $result['lusText'] = $count1[0]['n'];
        $result['lusBBox'] = $count2[0]['n'];
        $counts = AnnotationSetModel::getCriteria()
            ->where('sentence.idSentence', 'IN', $sentences)
            ->get(["count(idAnnotationSet) as a", "count(distinct idSentence) as s"]);
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $result['avgAS']= number_format($counts[0]['a'] / $counts[0]['s'], 3, $decimal, '');
        $count = ObjectFrameMMModel::getCriteria()
            ->where('objectMM.documentMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->groupBy("idObjectMM")
            ->get("count(*) as n");
        $sum = 0;
        foreach ($count as $row) {
            $sum += $row['n'];
        }
        $avg = ($sum / count($count)) * 0.040; // 40 ms por frame
        $result['avgDuration'] = number_format($avg, 3, $decimal, '');
        return $result;
    }

    //
    // Audition
    //

    private static function getSentencesAudition($query)
    {
        $query->setModel(SentenceModel::class)
            ->distinct()
            ->select("idSentence")
            ->where('documents.corpus.entry', 'IN', [
                'crp_curso_dataset'
            ]);

    }

    public static function audition(): array
    {
        $result = [];
        $sentences = function ($query) {
            self::getSentencesAudition($query);
        };
        $count = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct idSentence) as n");
        $result['sentences'] = $count[0]['n'];
        $count = ObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_curso_dataset'])
            ->get("count(distinct idObjectMM) as n");
        $result['bbox'] = $count[0]['n'];
        $count1 = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct lu.idFrame) as n");
        $count2 = ObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_curso_dataset'])
            ->get("count(distinct frameElement.idFrame) as n");
        $result['framesText'] = $count1[0]['n'];
        $result['framesBBox'] = $count2[0]['n'];
        $count1 = LabelModel::getCriteria()
            ->where('layer.annotationSet.sentence.idSentence', 'IN', $sentences)
            ->get("count(distinct frameElement.idFrameElement) as n");
        $count2 = ObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_curso_dataset'])
            ->get("count(distinct idFrameElement) as n");
        $result['fesText'] = $count1[0]['n'];
        $result['fesBBox'] = $count2[0]['n'];
        $count1 = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct lu.idLU) as n");
        $count2 = ObjectMMModel::getCriteria()
            ->where('idLU', 'IS', 'NOT NULL')
            ->where('documentMM.document.corpus.entry', 'IN', ['crp_curso_dataset'])
            ->get("count(distinct idLU) as n");
        $result['lusText'] = $count1[0]['n'];
        $result['lusBBox'] = $count2[0]['n'];
        $counts = AnnotationSetModel::getCriteria()
            ->where('sentence.idSentence', 'IN', $sentences)
            ->get(["count(idAnnotationSet) as a", "count(distinct idSentence) as s"]);
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $result['avgAS']= number_format($counts[0]['a'] / $counts[0]['s'], 3, $decimal, '');
        $count = ObjectFrameMMModel::getCriteria()
            ->where('objectMM.documentMM.document.corpus.entry', 'IN', ['crp_curso_dataset'])
            ->groupBy("idObjectMM")
            ->get("count(*) as n");
        $sum = 0;
        foreach ($count as $row) {
            $sum += $row['n'];
        }
        $avg = ($sum / count($count)) * 0.040; // 40 ms por frame
        $result['avgDuration'] = number_format($avg, 3, $decimal, '');
        ddump($result);
        return $result;
    }

    //
    // Multi30k
    //

    public static function multi30k(): array
    {
        $result = [];
        $count = ObjectSentenceMMModel::getCriteria()
            ->where('sentenceMM.sentence.documents.corpus.entry', 'IN', [
                'crp_oficina_com_sentenca_1',
                'crp_oficina_com_sentenca_2',
                'crp_oficina_com_sentenca_3',
                'crp_oficina_com_sentenca_4',
                'crp_oficina_sem_sentenca_1',
                'crp_oficina_sem_sentenca_2',
                'crp_oficina_sem_sentenca_3',
                'crp_oficina_sem_sentenca_4',
            ])
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->get([
                "count(distinct idSentenceMM) as n1",
                "count(distinct idObjectSentenceMM) as n2",
                "count(distinct frameElement.idFrame) as n3",
                "count(distinct idFrameElement) as n4"
            ]);
        $result['images'] = $count[0]['n1'];
        $result['bbox'] = $count[0]['n2'];
        $result['framesImage'] = $count[0]['n3'];
        $result['fesImage'] = $count[0]['n4'];
        $result['lusImage'] = 0;

        ////
        $dbDaisy = PersistenceManager::$capsule->connection('daisy');
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
        // Chart
        $dbFnbr = PersistenceManager::$capsule->connection('fnbr');
        $cmd = "SELECT month(tlDateTime) m,year(tlDateTime) y, count(*) n
         FROM fnbr_db.timeline t
where tablename='objectsentencemm'
group by month(tlDateTime),year(tlDateTime)";
        $rows = $dbFnbr->select($cmd, []);
        $chart = [];
        $sum = 0;
        foreach($rows as $row) {
            $sum += $row['n'];
            $chart[] = [
                'm' => $row['m'] . '/' . $row['y'],
                'value' => $sum
            ];
        }
        $chart[count($chart) - 1]['value'] = $result['bbox'];
        $result['chart'] = $chart;
        return $result;
    }

}