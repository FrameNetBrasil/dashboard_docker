<?php

namespace App\Resources;

use Orkester\Resource\BasicResource;

class AppResource extends BasicResource
{
    public function one($conditions, array $select = []): array|null
    {
      return $this->model::one($conditions, $select);
    }
}