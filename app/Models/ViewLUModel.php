<?php
namespace App\Models;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;


class ViewLUModel extends Model {

    public static function map(ClassMap $classMap): void
    {
        
        self::table('view_lu');
        self::attribute('idLU', key: Key::PRIMARY);
        self::attribute('name');
        self::attribute('senseDescription');
        self::attribute('active', type: Type::INTEGER);
        self::attribute('importNum', type: Type::INTEGER);
        self::attribute('incorporatedFE', type: Type::INTEGER);
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idLemma', key: Key::FOREIGN);
        self::attribute('idFrame', key: Key::FOREIGN);
        self::attribute('frameEntry');
        self::attribute('lemmaName');
        self::attribute('idLanguage', key: Key::FOREIGN);
        self::attribute('idPOS', key: Key::FOREIGN);
        self::associationOne('lemma', model: LemmaModel::class);
        self::associationOne('frame', model: FrameModel::class, key: 'idFrame');
        self::associationOne('language', model: LanguageModel::class, key: 'idLanguage');
        self::associationMany('annotationSets', model: ViewAnnotationSetModel::class, keys: 'idLU');
    }

    public static function listByFrame(int $idFrame) : array {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [];
        $filters[] = ['idLanguage','=', $idLanguage];
        $filters[] = ['idFrame','=', $idFrame];
        return self::list($filters, [
            'idLU',
            'name',
            'senseDescription'
        ], 'name');

    }

    public static function listCountByFrame(int $idFrame) : array {
        return self::getCriteria()
            ->select(['lemma.language.language','count(*) as count'])
            ->where('idFrame','=', $idFrame)
            ->groupBy('lemma.language.language')
            ->get()->all();
    }


}
