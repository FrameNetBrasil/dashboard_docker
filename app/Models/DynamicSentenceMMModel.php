<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Model;
use Orkester\Persistence\Repository;

class DynamicSentenceMMModel extends Model
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('dynamicsentencemm')
            ->attribute('idDynamicSentenceMM', key: Key::PRIMARY)
            ->attribute('startTime', type: Type::FLOAT)
            ->attribute('endTime', type: Type::FLOAT)
            ->attribute('origin', type: Type::INTEGER)
            ->attribute('idSentence', type: Type::INTEGER, key: Key::FOREIGN)
            ->attribute('idOriginMM', type: Type::INTEGER, key: Key::FOREIGN)
            ->associationOne('sentence', model: SentenceModel::class, key: 'idSentence:idSentence')
            ->associationOne('originMM', model: OriginMMModel::class, key: 'idOriginMM');
    }
    public static function listByDocument($idDocument): array
    {
        $cmd = <<<HERE
select `dynamicsentencemm`.`idDynamicSentenceMM`,
       `dynamicsentencemm`.`startTime`,
       `dynamicsentencemm`.`endTime`,
       `dynamicsentencemm`.`idSentence`,
       `sentence_1`.`text`
from `dynamicsentencemm`
         inner join `sentence` as `sentence_1` on `dynamicsentencemm`.`idSentence` = `sentence_1`.`idSentence`
         inner join `document_sentence` as `a3` on `sentence_1`.`idSentence` = `a3`.`idSentence`
         inner join `document` as `documents_2` on `a3`.`idDocument` = `documents_2`.`idDocument`
where `documents_2`.`idDocument` = {$idDocument}

HERE;
        return self::query($cmd);
    }
}
