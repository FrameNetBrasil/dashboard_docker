<?php
namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewWfLexemeLemmaModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        self::table('view_wflexemelemma');
        self::attribute('idWordForm', key: Key::PRIMARY);
        self::attribute('form');
        self::attribute('idLexeme',key:Key::FOREIGN);
        self::attribute('lexeme');
        self::attribute('idPOSLexeme',key:Key::FOREIGN);
        self::attribute('POSLexeme');
        self::attribute('idLanguage',key:Key::FOREIGN);
        self::attribute('idLexemeEntry',key:Key::FOREIGN);
        self::attribute('lexemeOrder');
        self::attribute('headWord');
        self::attribute('idLemma',key:Key::FOREIGN);
        self::attribute('idPOSLemma',key:Key::FOREIGN);
        self::attribute('POSLemma');
    }

}
