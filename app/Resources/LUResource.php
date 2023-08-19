<?php

namespace App\Resources;

use App\Models\LUModel;
use App\Services\AppService;
use Illuminate\Support\Collection;
use Orkester\Persistence\Criteria\Criteria;

class LUResource extends AppResource
{
    public function __construct(
        protected readonly AppService $app
    )
    {
        parent::__construct(LUModel::class);
    }

    public function getCriteria(): Criteria
    {
        return $this->model::getCriteria()
            ->where('lemma.idLanguage', '=', $this->app::getCurrentIdLanguage())
            ->where('frame.idLanguage', '=', $this->app::getCurrentIdLanguage());
    }

    public function listByName(string $name = ''): Collection
    {
        $criteria = $this->getCriteria()
            ->where('active', '=', 1)
            ->orderBy('frame.name')
            ->orderBy('name');

        if ($name != '') {
            $criteria->where('name', 'startswith', $name);
        }
        return $criteria->get([
            'idLU',
            'name',
            'senseDescription as definition',
            'idFrame',
            'frame.name as frameName'
        ]);
    }


}