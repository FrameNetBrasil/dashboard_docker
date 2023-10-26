<?php

namespace App\Resources;

use App\Models\FrameElementModel;
use App\Services\AppService;
use Illuminate\Support\Collection;
use Orkester\Persistence\Criteria\Criteria;

class FrameElementResource extends AppResource
{
    public function __construct(
        protected readonly AppService $app
    )
    {
        parent::__construct(FrameElementModel::class);
    }

    public function getCriteria(): Criteria
    {
        return $this->model::getCriteria()
            ->where('idLanguage', '=', $this->app::getCurrentIdLanguage())
            ->where('frame.entries.idLanguage', '=', $this->app::getCurrentIdLanguage());
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
            'idFrameElement',
            'name',
            'description as definition',
            'idFrame',
            'frame.entries.name as frameName'
        ]);
    }


}