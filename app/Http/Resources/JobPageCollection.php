<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class JobPageCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\JobResource';

    public function toArray($request)
    {

        return parent::toArray($request);
        return $this->collection;

        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }


}
