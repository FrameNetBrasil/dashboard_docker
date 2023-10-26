<?php

namespace App\Models;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class LabelFECETargetModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('view_labelfecetarget');
        self::attribute('idAnnotationSet', key: Key::PRIMARY);
        self::attribute('idSentence', type:Type::INTEGER);
        self::attribute('layerTypeEntry');
        self::attribute('idFrameElement', type:Type::INTEGER);
        self::attribute('idConstructionElement', type:Type::INTEGER);
        self::attribute('idGenericLabel', type:Type::INTEGER);
        self::attribute('startChar');
        self::attribute('endChar');
        self::attribute('rgbFg');
        self::attribute('rgbBg');
        self::attribute('idLanguage', type:Type::INTEGER);
        self::attribute('instantiationType');
        self::associationOne('sentence', model: SentenceModel::class, key: 'idSentence');
    }

}
