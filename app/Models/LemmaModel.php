<?php

namespace App\Models;

use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;

class LemmaModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('lemma');
        self::attribute('idLemma', key: Key::PRIMARY);
        self::attribute('name');
        self::attribute('version');
        self::attribute('idLanguage',type: Type::INTEGER);
        self::associationOne('entity', model: EntityModel::class);
        self::associationOne('pos', model: POSModel::class);
        self::associationOne('udpos', model: UDPOSModel::class);
        self::associationOne('language', model: LanguageModel::class);
        self::associationMany('lexemes', model: LexemeModel::class, associativeTable: 'lexemeentry');
        self::associationMany('lexemeentries', model: LexemeEntryModel::class, keys: 'idLemma');
        self::associationMany('lus', model: LUModel::class, keys: 'idLemma');
    }

    public static function updateLemmaForUdParse(array &$udParse)
    {
        foreach ($udParse as $i => $word) {
            if ($udParse[$i]['pos'] == 'PROPN') {
                $udParse[$i]['pos'] = 'NOUN';
            }
            $md5 = md5(strtolower($word['word']));
            $lemmas = static::getCriteria()
                ->select('idEntity', 'name', 'lexemes.wordforms.idEntity as idEntityWord')
                ->where('lexemes.wordforms.md5', '=', $md5)
//                ->where('udpos.udPOS', '=', $word['pos'])
                ->get()->toArray();
            if (empty($lemmas)) {
                $lemmas = static::getCriteria()
                    ->select('idEntity', 'name', 'lexemes.wordforms.idEntity as idEntityWord')
                    ->where('lexemes.wordforms.md5', '=', $md5)
                    ->get()->toArray();
            }
            foreach ($lemmas as $lemma) {
                $udParse[$i]['lemmas'][$lemma['idEntity']] = $lemma;
                // acrescentar as udFeatures cadastradas no banco para esta word
                if ($lemma['idEntityWord']) {
                    $rows = RelationModel::getCriteria()
                        ->select('idEntity2')
                        ->where('idRelationType', '=', '75')
                        ->where('idEntity1', '=', $lemma['idEntityWord'])
                        ->get()->toArray();
                    foreach ($rows as $row) {
                        $feat = UDFeatureModel::getCriteria()
                            ->select('typeinstance.info type', 'info')
                            ->where('idEntity', '=', $row['idEntity2'])
                            ->get()->toArray();
//                        mdump($feat);
                        $udParse[$i]['feature'][] = $feat[0]['type'] . ':' . $feat[0]['info'];
                    }
                }
                //

            }
            $isPronoun = false;
            foreach ($word['feature'] as $j => $feature) {
                if (str_starts_with($feature, 'PronType')) {
                    if ($feature == 'PronType:Tot') {
                        $udParse[$i]['feature'][$j] = $feature = 'PronType:Ind';
                    }
                    if ($feature != 'PronType:Art') {
                        $isPronoun = true;
                        break;
                    }
                }
            }
            if ($isPronoun) {
                $udParse[$i]['pos'] = 'PRON';
                $lemmas = static::getCriteria()
                    ->select('idEntity', 'name')
                    ->where('lexemes.wordforms.md5', '=', $md5)
                    ->where('udpos.udPOS', '=', 'PRON')
                    ->get()->toArray();
                foreach ($lemmas as $lemma) {
                    $udParse[$i]['lemmas'][$lemma['idEntity']] = $lemma;
                }
            }
        }
        /*
        foreach($udParse as $i => $word) {
            $md5 = md5(strtolower($word['word']));
            $lemmas = static::getCriteria()
                ->select('idEntity','name','lexemes.wordforms.idEntity idEntityWord')
                ->where('lexemes.wordforms.md5','=',$md5)
                ->where('udpos.udPOS','=',$word['pos'])
                ->get()->toArray();
            if (empty($lemmas)) {
                $lemmas = static::getCriteria()
                    ->select('idEntity','name','lexemes.wordforms.idEntity idEntityWord')
                    ->where('lexemes.wordforms.md5','=',$md5)
                    ->get()->toArray();
            }
            foreach($lemmas as $lemma) {
                $udParse[$i]['lemmas'][$lemma['idEntity']] = $lemma;
                // acrescentar as udFeatures cadastradas no banco para esta word
                if ($lemma['idEntityWord']) {
                    $rows = RelationModel::getCriteria()
                        ->select('idEntity2')
                        ->where('idRelationType', '=', '75')
                        ->where('idEntity1', '=', $lemma['idEntityWord'])
                        ->get()->toArray();
                    foreach($rows as $row) {
                        $feat = UDFeatureModel::getCriteria()
                            ->select('typeinstance.info type','info')
                            ->where('idEntity','=',$row['idEntity2'])
                            ->get()->toArray();
                        mdump($feat);
                        $udParse[$i]['feature'][] = $feat[0]['type'] . ':' . $feat[0]['info'];
                    }
                }
                //
            }
            $isPronoun = false;
            foreach($word['feature'] as $j => $feature){
                if (str_starts_with($feature, 'PronType')) {
                    if ($feature == 'PronType:Tot') {
                        $udParse[$i]['feature'][$j] = $feature = 'PronType:Ind';
                    }
                    if ($feature != 'PronType:Art') {
                        $isPronoun = true;
                        break;
                    }
                }
            }
            if ($isPronoun) {
                $udParse[$i]['pos'] = 'PRON';
                $lemmas = static::getCriteria()
                    ->select('idEntity','name')
                    ->where('lexemes.wordforms.md5','=',$md5)
                    ->where('udpos.udPOS','=','PRON')
                    ->get()->toArray();
                foreach($lemmas as $lemma) {
                    $lemma['cwe'] = LexemeEntryModel::getCriteria()
                        ->where('lemma.idEntity','=',$lemma['idEntity'])
                        ->count('*');
                    $udParse[$i]['lemmas'][$lemma['idEntity']] = $lemma;
                }
            }
        }
        */
    }


}
