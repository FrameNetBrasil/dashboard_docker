<?php

namespace App\Resources;

use App\Models\FrameModel;
use App\Services\AppService;
use Illuminate\Support\Collection;
use Orkester\Persistence\Criteria\Criteria;

class FrameResource extends AppResource
{
    public function __construct(
        protected readonly AppService $app
    )
    {
        parent::__construct(FrameModel::class);
    }

    public function getCriteria(): Criteria
    {
        return $this->model::getCriteria()
            ->where('idLanguage', '=', $this->app::getCurrentIdLanguage());
    }

    public function listByName(string $name = ''): Collection
    {
        $criteria = $this->getCriteria()
            ->where('active', '=', 1)
            ->orderBy('name');
        if ($name != '') {
            $criteria->where('name', 'startswith', $name);
        }
        return $criteria->get([
            'idFrame',
            'name',
            'description as definition'
        ]);
    }


}