<?php

namespace App\Http\Controllers\Api;

use App\Constant\JobConstant;
use App\Enum\IndustryTypeEnum;
use App\Http\Controllers\Controller;
use App\Service\JobService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class JobController extends Controller
{
    private $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    // 工作列表、工作经验等筛选条件
    public function getList(Request $request)
    {
        Log::debug(\json_encode($request->toArray()));

        $jobList = $this->jobService->getList($request->toArray());
        $jobList = $jobList->additional([
            'code' => 0,
            'msg' => 'Success',
        ]);
        return $jobList;

        $result = [
            'code' => 0,
            'msg' => '获取工作列表成功',
            'data' => $jobList
        ];

        return $this->response($result);
    }

    // 相关工作列表
    public function getListRelated()
    {
        $jobList = $this->jobService->getRelatedList();

        $result = [
            'code' => 0,
            'msg' => '获取工作列表成功',
            'data' => $jobList
        ];

        return $this->response($result);
    }

    // 推荐职位列表
    public function getListRecommend()
    {
        $jobList = $this->jobService->getRecommendList();

        $result = [
            'code' => 0,
            'msg' => '获取工作列表成功',
            'data' => $jobList
        ];

        return $this->response($result);
    }

    // 工作详情
    public function getDetailById(int $id)
    {
        $job = $this->jobService->getById($id) ?? [];
        $result = [
            'code' => 0,
            'msg' => '获取工作详情成功',
            'data' => $job
        ];

        return $this->response($result);
    }

    //根据上级ID查询职位类型
    public function getPositionTypeListByParentId(Request $request)
    {
        $parentId = $request->get('parent_id', 0);
    }

    // 公司行业
    public function getIndustryList()
    {
        $industryList = IndustryTypeEnum::IndustryList;

        $result = [
            'code' => 0,
            'msg' => '获取公司行业列表成功',
            'data' => $industryList
        ];

        return $this->response($result);
    }

    // 筛选等配置
    public function getSearchFilterConfig()
    {
        $jobSearchFilterConfig = JobConstant::SEARCH_FILTER_CONFIG;

        $result = [
            'code' => 0,
            'msg' => '获取公司行业列表成功',
            'data' => $jobSearchFilterConfig
        ];

        return $this->response($result);
    }

}
//八、反馈
