<?php

namespace App\Resources;

use App\Models\LanguageModel;
use App\Services\AppService;
use Orkester\Persistence\Criteria\Criteria;

class LanguageResource extends AppResource
{
    public function __construct(
        protected readonly AppService $app,
        protected readonly LanguageModel $languageModel
    )
    {
        parent::__construct($languageModel::class);
    }

    public function getCriteria(): Criteria
    {
        return $this->model::getCriteria()
            ->where('idLanguage', '=', $this->app::getCurrentIdLanguage());
    }

    public function getCriteriaLanguage(string $language): Criteria
    {
        return $this->model::getCriteria()
            ->where('language', '=', $language);
    }

}
