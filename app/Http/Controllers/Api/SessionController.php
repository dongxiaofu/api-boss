<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\JobHunterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Model\Session;


class SessionController extends Controller
{
    private $jobHunterService;

    public function __construct(JobHunterService $jobHunterService)
    {
        $this->jobHunterService = $jobHunterService;
    }


    // 用户信息
    public function getList(Request $request)
    {
        $user = Session::limit(50)
            ->orderBy('id', 'desc')
            ->where('status', 1)
            ->get();
        $result = [
            'code' => 0,
            'msg' => '获取会话列表成功',
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


    // 获取IP
    public function getIP(Request $request)
    {
        $result = [
            'code' => 0,
            'msg' => '更新求职者信息成功',
            'data' => [
                'ip' => $_SERVER['REMOTE_ADDR']
            ]
        ];
        return $this->response($result);
    }

    // 清理会话
    public function clear(Request $request)
    {
//        <select v-model="actionId">
//        <option :value="1">清理对话内容</option>
//        <option :value="2">清理对话列表</option>
//        </select>
//        <select v-model="actionTime">
//        <option value="5">5天</option>
//        <option :value="7">7天</option>
//        </select>
        $actionTypeSession = 1;
        $actionTypeMessage = 2;
        $timeType5 = 5;
        $timeType7 = 7;
        $actionType = $request->get('action_type', 0);
        $timeType = $request->get('time_type', 0);
        $sessionId = $request->get('session_id', 0);
        if ($actionType == 0 || $timeType == 0) {
            $result = [
                'code' => 0,
                'msg' => '参数不正确',
                'data' => []
            ];
        } else {
            if ($actionType == $actionTypeSession) {
                $this->clearSession($timeType);
            } else {
                $this->clearMessage($sessionId, $timeType);
            }
            $result = [
                'code' => 0,
                'msg' => '清理成功',
                'data' => []
            ];
        }

        return $this->response($result);
    }

    private function clearSession($timeType)
    {
        $date = $this->getDate($timeType);
        // status:是否显示：0.隐藏；1.显示
        $sql = 'update `session` set status = :status ';
        $sql .= 'where status = 1 ';
        $sql .= 'and date_text <= :date_text';
        $binds = [':status' => 0, 'date_text' => $date];
        $count = DB::update($sql, $binds);
        return $count;
    }

    // todo 游客那边也看不到被清理过的对话。
    private function clearMessage($sessionId, $timeType)
    {
        $date = $this->getDate($timeType);
        $sql = 'update message set status = :status ';
        $sql .= 'where status = 1 ';
        $sql .= 'and session_id = :session_id ';
        $sql .= 'and date_text <= :date_text';
        $binds = [':status' => 0, 'date_text' => $date, ':session_id' => $sessionId];
        $count = DB::update($sql, $binds);
        return $count;
    }

    private function getDate($timeType)
    {
        return date('Ymd', strtotime('-' . $timeType . ' days'));;
    }
}
