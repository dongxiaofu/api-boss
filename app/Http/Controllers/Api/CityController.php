<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\DistrictService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    private $districtService;

    public function __construct(DistrictService $districtService)
    {
        $this->districtService = $districtService;
    }

    // 根据城市首字母查询城市列表
    public function getListByFirstLetter(Request $request)
    {
        $firstLetter = $request->get('first_letter', '');
        $cityList = $this->districtService->getListByFirstLetter($firstLetter,20);

        $result = [
            'code' => 0,
            'msg' => '获取城市列表成功2',
            'data' => $cityList
        ];

        return $this->response($result);
    }

    //根据城市ID查询城区列表
    public function getListByParentId(Request $request)
    {
        $parentId = $request->get('parent_id', 76);

        $cityList = $this->districtService->getAreaByParentId($parentId,20);

        $result = [
            'code' => 0,
            'msg' => '获取城区列表成功',
            'data' => $cityList
        ];

        return $this->response($result);
    }

    //热门城市
    public function getListByHot(Request $request)
    {
        $cityList = $this->districtService->getHotCity(20);

        $result = [
            'code' => 0,
            'msg' => '获取热门城市列表成功',
            'data' => $cityList
        ];

        return $this->response($result);
    }
}
