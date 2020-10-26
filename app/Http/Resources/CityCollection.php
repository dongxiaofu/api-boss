<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CityCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\CityResource';

    public function toArray($request)
    {
        return $this->collection;

        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }


}
