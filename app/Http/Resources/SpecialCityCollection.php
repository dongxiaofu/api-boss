<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SpecialCityCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\LetterCityCollection';

    public function toArray($request)
    {
        return $this->collection;
    }


}
