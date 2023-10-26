<?php

namespace App\Models;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class WordFormModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('wordform');
        self::attribute('idWordForm', key: Key::PRIMARY);
        self::attribute('form');
        self::attribute('md5');
        self::associationOne('entity', model: EntityModel::class, key: 'idEntity');
        self::associationOne('lexeme', model: LexemeModel::class, key: 'idLexeme');
    }


    public function hasLUCandidates($wordformList)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $list = [];
        foreach($wordformList as $i => $wf) {
            if ($wf != '') {
                $wf = str_replace("'","\'", $wf);
                $list[$i] = "'{$wf}'";
            }
        }
        $in = implode(',', $list);
        $criteria = $this->getCriteria();
        $criteria->select('form, count(lexeme.lexemeentries.lemma.lus.idLU) as n');
        $criteria->where("form IN ($in)");
        $criteria->where("lemma.idLanguage = {$idLanguage}");
        $criteria->groupBy("form");
        $criteria->having("count(lexeme.lexemeentries.lemma.lus.idLU) > 0");
        return ($criteria->asQuery()->chunkResult('form','n'));
    }

}

