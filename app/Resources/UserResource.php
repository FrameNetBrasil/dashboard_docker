<?php

namespace App\Resources;

use App\Models\UserModel;
use Illuminate\Support\Collection;
use Orkester\Persistence\Criteria\Criteria;

class UserResource extends AppResource
{
    public function __construct(
    )
    {
        parent::__construct(UserModel::class);
    }

    public function getGroups(int $idUser) {
        return $this->getCriteria()
            ->where('idUser','=', $idUser)
            ->get('groups.*');
    }

}