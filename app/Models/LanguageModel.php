<?php

namespace App\Models;
use App\Services\AppService;
use Carbon\Traits\ToStringFormat;
use Orkester\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;

class LanguageModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        self::table('language');
        self::attribute('idLanguage', key: Key::PRIMARY);
        self::attribute('language');
        self::attribute('description');
    }

    public static function getIdFromLanguage(string $language): int {
        $language = self::one(['language','=',$language]);
        return $language['idLanguage'];
    }

    public static function getLanguageFromId(int $idLanguage): string {
        $language = self::find($idLanguage);
        return $language['language'];
    }

    public static function getAllLanguages(): array {
        $language = self::list([], ['language'], 'idLanguage');
        $languages = [];
        foreach ($language as $innerArray) {
            foreach ($innerArray as $value) {
                $languages[] = $value;
            }
        }
        
        unset($languages[0]);        
        $languages = array_values($languages);

        return $languages;
    }

    public static function listAll() : array {
        return self::list([], [
            'idLanguage',
            'language',
            'description'
        ], 'language');
    }

    public static function listForSelect(array $languages) : array {
        $filter = [];
        if (!empty($languages)) {
            $filter = ['language', 'IN', $languages];
        }
        return self::list($filter, [
            'idLanguage',
            'language',
            'description'
        ], 'language');
    }

}
