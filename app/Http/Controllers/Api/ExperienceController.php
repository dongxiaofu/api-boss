<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\ExperienceService;
use Illuminate\Http\Request;


class ExperienceController extends Controller
{
    private $experienceService;

    public function __construct(ExperienceService $experienceService)
    {
        $this->experienceService = $experienceService;
    }

    // 获取工作经验列表
    public function list(Request $request)
    {
        $userId = $request->get('user_id');
        $experienceList = $this->experienceService->getList((int)$userId);
        $result = [
            'code' => 0,
            'msg' => '获取工作经验列表成功',
            'data' => $experienceList
        ];
        return $this->response($result);
    }

    // 提交工作经验
    public function save(Request $request)
    {
        $result = $this->updateOrCreate($request);
        return $this->response($result);
    }

    // 新增工作经验
    public function create(Request $request)
    {
        $result = $this->updateOrCreate($request);
        return $this->response($result);
    }

    // 删除工作经验
    public function delete(Request $request)
    {
        $id = $request->get('id', 0);
        $bool = $this->experienceService->delete((int)$id);
        $result = [
            'code' => 0,
            'msg' => '删除工作经验成功',
            'data' => []
        ];
        return $result;
    }

    /// 保存或更新用户经验
    private function updateOrCreate(Request $request)
    {
        $params = $request->all();
        // 可在save中抛出异常，此处捕捉异常
        $bool = $this->experienceService->save($params);
        $result = [
            'code' => 0,
            'msg' => '提交工作经验成功',
            'data' => []
        ];
        return $result;
    }
}
