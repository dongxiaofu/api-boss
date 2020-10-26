<?php
declare(strict_types=1);

namespace App\Service;

use App\Enum\DistrictHotEnum;
use App\Enum\DistrictMunicipalityEnum;
use App\Enum\DistrictTypeEnum;
use App\Http\Resources\CityCollection;
use App\Http\Resources\LetterCityCollection;
use App\Model\District;
use function Complex\add;
use function PHPSTORM_META\map;

class DistrictService
{
    public function getHotCity(int $limit = 5)
    {
        $cityList = District::take($limit)
//            ->where('type', DistrictTypeEnum::CITY)
            ->where('is_hot', DistrictHotEnum::YES)
//            ->orWhere('is_municipality', DistrictMunicipalityEnum::YES)
            ->get();
        return new CityCollection($cityList);

        return $cityList;
    }

    public static function getListByOneFirstLetter($firstLetter, int $limit = 5)
    {
        $cityList = District::take($limit)
            ->where('first_letter', $firstLetter)
            ->where('type', DistrictTypeEnum::CITY)
            ->get();
//        $cityList2 = District::take($limit)
//            ->where('first_letter', $firstLetter)
//            ->where('type', DistrictTypeEnum::CITY)
//            ->where('is_municipality', DistrictMunicipalityEnum::YES)
//            ->get();
//        $cityList2 = $cityList2->reject(function ($city) {
//            return $city->type == DistrictTypeEnum::PROVINCE;
//        });
//        foreach ($cityList2 as $city) {
//            $cityList->add($city);
//        }
        return $cityList;
    }

    public function getListByFirstLetter($firstLetters, int $limit = 5)
    {
        $cityList = new LetterCityCollection([]);
        $firstLettersArray = explode(',', strtoupper($firstLetters));
        $cityList->setFirstLetters($firstLettersArray);
        return $cityList;
    }

    public function getAreaByParentId(int $parentId, int $limit = 5)
    {
        $cityList = District::take($limit)
            ->where('pid', $parentId)
//            ->where('type', DistrictTypeEnum::AREA)
            ->get();

        return new CityCollection($cityList);

        return $cityList;
    }

    public static function getNameBy(int $id)
    {
        $district = District::select('district_name')->where('id', $id)->first();
        $name = ($district['district_name']) ?? '';
        return $name;
    }
}
