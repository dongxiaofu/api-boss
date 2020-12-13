<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Message;
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
        $users = Session::limit(50)
            ->orderBy('id', 'desc')
            ->where('status', 1)
            ->get();
        foreach ($users as &$user) {
            $message = Message::select('message')
                ->where('session_id', $user->id)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->first();
            $newMessageStr = isset($message['message']) ? $message['message'] : '';
            $newMessage = explode('|', $newMessageStr);
            $messageType = isset($newMessage[1]) ? $newMessage[1] : 2;
            $user->new_message = $messageType == 3 ? '图片' : $newMessage[0];
            $user->updated_at = $carbon = $user->updated_at;
            $user->last_online_time = sprintf('%d-%d-%d %d:%d:%d',
                $carbon->year, $carbon->month, $carbon->day, $carbon->hour, $carbon->minute, $carbon->second);
        }
        $result = [
            'code' => 0,
            'msg' => '获取会话列表成功',
            'data' => $users
        ];
        return $this->response($result);
    }

    public function getById(Request $request)
    {
        $sessionId = $request->get('session_id', 0);
        $sessionId = (int)$sessionId;
        $session = Session::find($sessionId);
        $result = [
            'code' => 0,
            'msg' => '获取求职者信息成功',
            'data' => $session
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
        $actionTypeSession = 2;
        $actionTypeMessage = 1;
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
//        var_dump($sql, $binds, $count);exit;
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

    // 屏蔽
    public function blockSwitch(Request $request)
    {
        $sessionId = $request->get('session_id', 0);
        // 是否屏蔽：0.屏蔽；1.未屏蔽
        $actionCode = $request->get('action_code', 0);

        $result = [
            'code' => 0,
            'msg' => '屏蔽成功',
            'data' => []
        ];

        try {
            $this->setBlock($sessionId, $actionCode);
        } catch (\Exception $exception) {
            $result = [
                'code' => 0,
                'msg' => '屏蔽失败',
                'data' => []
            ];
        }

        return $result;

    }

    private function setBlock($sessionId, $actionCode)
    {
        // 屏蔽会话
        $session = Session::find($sessionId);
        $session->is_block = (int)$actionCode;
        $session->save();
        // 屏蔽游客
        $customerId = $session->customer_id;
        $sql = 'update customer set is_block = :is_block where id = :id';
        $binds = [':is_block' => $actionCode, ':id' => $customerId];
        $count = DB::update($sql, $binds);
    }

    // 备注
    public function remark(Request $request)
    {
        $sessionId = $request->get('session_id', 0);
        $remark = $request->get('remark', '');
        $title = $request->get('title', '');

        $result = [
            'code' => 0,
            'msg' => '备注成功',
            'data' => []
        ];

        try {
            if (empty($sessionId)) {
                throw new \Exception('sessionId 不能为空', -1);
            }
            $session = Session::find($sessionId);
            $remark && $session->remark = $remark;
            $title && $session->title = $title;
            $session->save();
        } catch (\Exception $exception) {
            $result = [
                'code' => 0,
                'msg' => $exception->getMessage(),
                'data' => []
            ];
        }

        return $result;
    }

    public function updateOnlineStatus(Request $request)
    {
        $sessionId = intval($request->get('sessionId', 0));
        $isOnLine = intval($request->get('isOnLine', 0));

        if (empty($userId)) {
            $result = [
                'code' => -1,
                'msg' => '参数不正确',
                'data' => []
            ];
            return $this->response($result);
        }

        try {
            $user = Session::find($sessionId);
            $user->is_online = $isOnLine;
            $user->save();
            $result = [
                'code' => 0,
                'msg' => '更新成功',
                'data' => []
            ];
        } catch (\Exception $exception) {
            $result = [
                'code' => -1,
                'msg' => $exception->getMessage(),
                'data' => []
            ];
        }

        return $this->response($result);
    }
}
