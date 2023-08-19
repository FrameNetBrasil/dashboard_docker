<?php

namespace App\Models;


use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;

class SentenceModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('sentence');
        self::attribute('idSentence', key: Key::PRIMARY);
        self::attribute('text');
        self::attribute('paragraphOrder', type: Type::INTEGER);
        self::attribute('idParagraph', key: Key::FOREIGN);
        self::attribute('idLanguage', key: Key::FOREIGN);
        self::attribute('idDocument', key: Key::FOREIGN);
        self::associationOne('paragraph', model: ParagraphModel::class, key: 'idParagraph');
        self::associationOne('document', model: DocumentModel::class, key: 'idDocument');
        self::associationOne('language', model: LanguageModel::class, key: 'idLanguage');
        self::associationMany('sentenceMM', model: SentenceMMModel::class, keys: 'idSentence:idSentence');
        self::associationMany('documents', model: DocumentModel::class, associativeTable: 'document_sentence');
    }

    public static function getWordsChars(int $idSentence): object
    {
        $text = self::getCriteria()
            ->select('text')
            ->where("idSentence = {$idSentence}")
            ->asResult()[0]['text'];
        $array = [];
        $punctuation = " .,;:?/'][\{\}\"!@#$%&*\(\)-_+=“”";
        mb_internal_encoding("UTF-8"); // this IS A MUST!! PHP has trouble with multibyte when no internal encoding is set!
        $i = 0;
        for ($j = 0; $j < mb_strlen($text); $j++) {
            $char = mb_substr($text, $j, 1);
            $break = (mb_strpos($punctuation, $char) !== false);
            if ($break) {
                $word = mb_substr($text, $i, $j - $i);
                $array[$i] = $word;
                $array[$j] = $char;
                $i = $j + 1;
            }
        }
        $chars = [];
        $order = 1;
        foreach ($array as $startChar => $wordForm) {
            $endChar = $startChar + mb_strlen($wordForm) - 1;
            $lWordForm = $wordForm;//str_replace("'", "\\'", $wordForm);
            $words[(string)$order] = [
                'order' => $order,
                'word' => $lWordForm,
                'startChar' => $startChar,
                'endChar' => $endChar
            ];
            for ($pos = (int)$startChar; $pos <= $endChar; $pos++) {
                $o = $pos - $startChar;
                $chars[$pos] = [
                    'offset' => (string)$o,
                    'char' => mb_substr($wordForm, $o, 1), // tf8_encode($wordForm{$o}), //str_replace("'", "\\'", $wordForm{$o}),
                    'order' => $order
                ];
            }
            ++$order;
        }
        $wordsChars = new \StdClass();
        $wordsChars->words = $words;
        $wordsChars->chars = $chars;
        return $wordsChars;
    }


}
