<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\JobHunterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Model\Message;


class MessageController extends Controller
{
    private $jobHunterService;

    public function __construct(JobHunterService $jobHunterService)
    {
        $this->jobHunterService = $jobHunterService;
    }


    // 用户信息
    public function getList(Request $request)
    {
        $today = date('Ymd');
        $sevenDaysAgo = date('Ymd', strtotime('-7 days'));
        $messages = Message::select('*')
            ->where('date_text', '<=', $today)
            ->where('date_text', '>=', $sevenDaysAgo)
            ->get();
        $messagesGroupByDate = [];
        foreach ($messages as $message) {
            $date = $message['date_text'];
//            $message->created_at = date('Y-m-d H:i:s', $message->created_at);
//            $year
//            * @property      int                 $yearIso
//            * @property      int                 $month
//            * @property      int                 $day
//            * @property      int                 $hour
//            * @property      int                 $minute
//            * @property      int                 $second
            $carbon = $message->created_at;
            $message->post_time = sprintf('%d-%d-%d %d:%d:%d',
                $carbon->year, $carbon->month, $carbon->day,$carbon->hour, $carbon->minute, $carbon->second);;
            $messagesGroupByDate[$date][] = $message;
        }
        // 花时间多
        $messages = [];
        foreach ($messagesGroupByDate as $key => $v) {
            $item['date'] = $key;
            $item['messages'] = $v;
            $messages[] = $item;
        }
        $result = [
            'code' => 0,
            'msg' => '获取会话列表成功',
            'data' => $messages
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
