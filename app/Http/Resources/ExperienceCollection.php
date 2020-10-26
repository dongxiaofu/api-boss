<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ExperienceCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\ExperienceResource';

    public function toArray($request)
    {
        return $this->collection;
    }


}
