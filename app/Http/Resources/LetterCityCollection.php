<?php

namespace App\Http\Resources;

use App\Service\DistrictService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LetterCityCollection extends ResourceCollection
{
    // 可有可无？
    public $collects = 'App\Http\Resources\CityResource';

    private $firstLetters = [];

    public function setFirstLetters(array $firstLetters)
    {
        foreach ($firstLetters as $letter) {
            array_push($this->firstLetters, $letter);
        }
    }

    public function toArray($request)
    {
        $result = [];
        foreach ($this->firstLetters as $firstLetter) {
            $cityList = DistrictService::getListByOneFirstLetter($firstLetter);
            if (is_null($cityList) || $cityList->count() == 0) {
                continue;
            }

            $item = ['category' => $firstLetter, 'city_items' => CityResource::collection($cityList)];
            array_push($result, $item);
        }
        return $result;
    }


}
