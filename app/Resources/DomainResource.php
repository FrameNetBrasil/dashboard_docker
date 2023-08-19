<?php

namespace App\Resources;

use App\Models\DomainModel;
use App\Services\AppService;

class DomainResource extends AppResource
{
    public function __construct(
        protected readonly AppService $app
    )
    {
        parent::__construct(DomainModel::class);
    }

    public function listForSelection(): array {
        return $this->model::list(['idLanguage','=', $this->app::getCurrentIdLanguage()],['idDomain as id','name'],'name');
    }

}