<?php

namespace App\Services;

use App\Models\AnnotationSetModel;
use App\Models\CorpusModel;
use App\Models\DocumentModel;
use App\Models\DynamicBBoxMMModel;
use App\Models\DynamicObjectMMModel;
use App\Models\LabelModel;
use App\Models\ObjectFrameMMModel;
use App\Models\ObjectMMModel;
use App\Models\ObjectSentenceMMModel;
use App\Models\SentenceModel;
use App\Models\StaticAnnotationMMModel;
use App\Models\StaticObjectMMModel;
use App\Models\StaticObjectSentenceMMModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Enum\Join;
use Orkester\Persistence\PersistenceManager;
use PHPSQLParser\builders\IndexTypeBuilder;

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

    private static function getSentencesFrame2()
    {
        return SentenceModel::getCriteria()
            ->distinct()
            ->select("idSentence")
            ->where('documents.corpus.entry', 'IN', [
                'crp_pedro_pelo_mundo'
            ]);
    }

    public static function frame2(): array
    {
        $result = [];
        $sentences = self::getSentencesFrame2();
        $count = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct idSentence) as n");
        $result['sentences'] = $count[0]['n'];
        $count = DynamicObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->get("count(distinct idDynamicObjectMM) as n");
        $result['bbox'] = $count[0]['n'];
        $count1 = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct lu.idFrame) as n");
        $count2 = DynamicObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->get("count(distinct frameElement.idFrame) as n");
        $result['framesText'] = $count1[0]['n'];
        $result['framesBBox'] = $count2[0]['n'];
        $count1 = LabelModel::getCriteria()
            ->where('layer.annotationSet.sentence.idSentence', 'IN', $sentences)
            ->get("count(distinct frameElement.idFrameElement) as n");
        $count2 = DynamicObjectMMModel::getCriteria()
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->where('document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->get("count(distinct idFrameElement) as n");
        $count3 = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(*) as n");
        $result['fesText'] = $count1[0]['n'];
        $result['fesBBox'] = $count2[0]['n'];
        $result['asText'] = $count3[0]['n'];
        $count1 = AnnotationSetModel::getCriteria()
            ->where('idSentence', 'IN', $sentences)
            ->get("count(distinct lu.idLU) as n");
        $count2 = DynamicObjectMMModel::getCriteria()
            ->where('idLU', 'IS', 'NOT NULL')
            ->where('document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo'])
            ->get("count(distinct idLU) as n");
        $result['lusText'] = $count1[0]['n'];
        $result['lusBBox'] = $count2[0]['n'];
        $counts = AnnotationSetModel::getCriteria()
            ->where('sentence.idSentence', 'IN', $sentences)
            ->get(["count(idAnnotationSet) as a", "count(distinct idSentence) as s"]);
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $result['avgAS'] = number_format($counts[0]['a'] / $counts[0]['s'], 3, $decimal, '');
        $count = DynamicBBoxMMModel::getCriteria()
            ->where('dynamicObjectMM.document.corpus.entry', 'IN', ['crp_pedro_pelo_mundo'])
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

    //
    // Audition
    //

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

    //
    // Multi30k
    //

    public static function multi30k(): array
    {
        $result = [];
        $count = StaticAnnotationMMModel::getCriteria()
            ->where('staticObjectSentenceMM.staticSentenceMM.sentence.documents.corpus.entry', 'IN', [
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
            ])
            ->where('idFrameElement', 'IS', 'NOT NULL')
            ->get([
//                "count(distinct staticObjectSentenceMM.idStaticSentenceMM) as n1",
                "count(distinct idStaticObjectSentenceMM) as n2",
                "count(distinct frameElement.idFrame) as n3",
                "count(distinct idFrameElement) as n4"
            ]);
        $count2 = StaticObjectSentenceMMModel::getCriteria()
            ->where('staticSentenceMM.sentence.documents.corpus.entry', 'IN', [
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
            ])
            ->where('staticAnnotationMM.idFrameElement', 'IS', 'NOT NULL')
            ->get([
                "count(distinct idStaticSentenceMM) as n1",
            ]);
        $result['images'] = $count2[0]['n1'];
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
//        $dbFnbr = PersistenceManager::$capsule->connection('fnbr');
//        $cmd = "SELECT year(tlDateTime) y, month(tlDateTime) m, count(*) n
//         FROM fnbr_db.timeline t
//where (tablename='objectsentencemm') or (tablename='staticannotationmm')
//group by year(tlDateTime),month(tlDateTime)
//order by 1,2;";
//        $rows = $dbFnbr->select($cmd, []);
//        $chart = [];
//        $sum = 0;
//        foreach ($rows as $row) {
//            $sum += $row['n'];
//            $chart[] = [
//                'm' => $row['m'] . '/' . $row['y'],
//                'value' => $sum
//            ];
//        }
//        //$chart[count($chart) - 1]['value'] = $result['bbox'];
        $result['chart'] = self::multi30kChart();
        return $result;
    }

    public static function multi30kChart(): array
    {
        $dbFnbr = PersistenceManager::$capsule->connection('fnbr');
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
                'm' => $m. '/' . $y,
                'value' => $sum
            ];
        }
        return $chart;
    }

    public static function updateTable($data)
    {
        $dbDaisy = PersistenceManager::$capsule->connection('daisy');
        $now = date('Y-m-d H:i:s');
        $frame2_avg_sentence = str_replace(',', '.', $data->frame2['avgDuration']);
        $frame2_avg_obj = str_replace(',', '.', $data->frame2['avgAS']);
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
 multi30k_eno_lome = {$data->multi30k['enoFrames']}
 where idDashboard = 1
";
        $dbDaisy->update($cmd);
    }

    public static function mustCalculate(): bool
    {
        $dbFnbr = PersistenceManager::$capsule->connection('fnbr');
        $cmd = "SELECT max(tlDateTime) as lastAnnotationTime
         FROM fnbr_db.timeline t
where (tablename='objectsentencemm') or (tablename='staticannotationmm')";
        $rows = $dbFnbr->select($cmd, []);
        $lastAnnotationTime = is_object($rows[0]) ? $rows[0]->lastAnnotationTime : $rows[0]['lastAnnotationTime'];
        $dbDaisy = PersistenceManager::$capsule->connection('daisy');
        $cmd = "SELECT max(timeLastUpdate) as lastUpdateTime
         FROM dashboard
";
        $rows = $dbDaisy->select($cmd, []);
        $lastUpdateTime = is_object($rows[0]) ? $rows[0]->lastUpdateTime : $rows[0]['lastUpdateTime'];
        return $lastAnnotationTime > $lastUpdateTime;
    }

    public static function getFromTable($data)
    {
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $dbDaisy = PersistenceManager::$capsule->connection('daisy');
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
    }

}
