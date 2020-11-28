<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Service\JobHunterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;


class UserController extends Controller
{
    private $jobHunterService;

    public function __construct(JobHunterService $jobHunterService)
    {
        $this->jobHunterService = $jobHunterService;
    }


    // 用户信息
    public function getUsers(Request $request)
    {
        $user = User::all();
        $result = [
            'code' => 0,
            'msg' => '获取客服列表成功',
            'data' => $user
        ];
        return $this->response($result);
    }

    // 用户信息
    public function getById(Request $request)
    {
        $userId = $request->get('user_id', 0);
        $userId = (int)$userId;
//        var_dump($userId);exit;
        $jobHunter = $this->jobHunterService->getByUserId($userId);
//        var_dump($jobHunter);exit;
        $result = [
            'code' => 0,
            'msg' => '获取求职者信息成功',
            'data' => $jobHunter
        ];
        return $this->response($result);
    }

    // 保存用户信息
    public function save(Request $request)
    {
        $params = $request->all();
        try {
            $this->jobHunterService->save($params);
            $result = [
                'code' => 0,
                'msg' => '更新求职者信息成功2',
                'data' => []
            ];
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            $result = [
                'code' => -1,
                'msg' => '更新求职者信息失败',
                'data' => []
            ];
        }

        return $this->response($result);
    }

    // 保存用户个人优势
    public function saveAdvantage(Request $request)
    {
//        $userId = $request->get('user_id', 0);
//        $advantage = $request->get('advantage', '');
        $params = $request->all();
        $userId = $params['user_id'] ?? 0;
        $advantage = $params['advantage'] ?? '';
        // 该不该在这里检查数据库操作的结果？
        $bool = $this->jobHunterService->saveAdvantage((int)$userId, $advantage);
        $result = [
            'code' => 0,
            'msg' => '更新求职者信息成功',
            'data' => []
        ];
        return $this->response($result);
    }
}
