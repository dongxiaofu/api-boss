<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Model\Customer;
use App\Model\Session;
use App\Model\User;
use App\Model\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class WebSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'web-socket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WebSocket服务';

    /**
     * 方便查找日志
     * @var string
     */
    protected $logTag = 'convert-too-pinyin';

    protected $requestCollection;


    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->requestCollection = new Collection();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $this->ws();
    }

    private function ws()
    {
        //创建WebSocket Server对象，监听0.0.0.0:9502端口
        $ws = new \Swoole\WebSocket\Server('0.0.0.0', 9502);
//        $type = 2;
//        $userId = 1;


        //监听WebSocket连接打开事件
        $ws->on('open', function ($ws, $request) {
//            var_dump($request);
            $getData = $request->get;
            $type = intval($getData['type']);
            $userId = intval($getData['userId']);
            $requestId = $request->fd;
            if ($type == 1) {
                $user = User::find($userId);
                $user->fn_id = $requestId;
                $user->save();
                $sessionId = 0;
            } else {
                // 创建游客账号
                $customer = new Customer();
                $customer->name = '游客' . strval(mt_rand(1, 200));
                $customer->fn_id = $requestId;
                $customer->save();
                $customerId = $customer->id;
                // 创建会话
                $session = new Session();
                $session->user_id = $userId;
                $session->customer_id = $customerId;
                $session->save();
                $sessionId = $session->id;
                $this->info('创建游客账号 start');
                var_dump($sessionId);
                $this->info('创建游客账号 end');
//                $userId = $customerId;
            }

            $requestJson = Cache::get('request2');
//            var_dump($requestJson);
            if (is_null($requestJson)) {
                var_dump($requestId);
                $request2[] = $requestId;
            } else {
                $request2 = \json_decode($requestJson, true);
                $request2[] = $requestId;
            }
            $requestJson = \json_encode($request2);
            Cache::put('request2', $requestJson);
            $msg = '-1|' . $requestId . '|' . $userId . '|' . $sessionId;
            $this->info('msg start2===========');
            $this->info($msg);
            $this->info('msg end2===========');
            $ws->push($request->fd, $msg);
        });

        //监听WebSocket消息事件
        $ws->on('message', function ($ws, $frame) {
            $requestJson = Cache::get('request2');
            if (is_null($requestJson)) {
                return;
            }
//            var_dump($requestJson);
            $request2 = \json_decode($requestJson, true);
            $data = $frame->data;

//            let text = type + '|';
//            text += userId + '|';
//            text += this.fd + '|';
//            text += sessionId + '|';
//            text += receiverId + '|' + username + '|' + content;

//            array(7) {
//                [0]=>
//  string(1) "1"
//                [1]=>
//  string(1) "1"
//                [2]=>
//  string(1) "2"
//                [3]=>
//  string(1) "0"
//                [4]=>
//  string(2) "67"
//                [5]=>
//  string(23) "user_0.6214001076321538"
//                [6]=>
//  string(4) "1111"
//}

//            let text = type + '|';
//            text += userId + '|';
//            text += this.fd + '|';
//            text += sessionId + '|';
//            text += receiverId + '|' + username + '|' + content;

            $arr = explode('|', $data);
            var_dump($arr);
            $type = $arr[0];
            array_shift($arr);
            $userId = intval($arr[0]);
            array_shift($arr);
            array_shift($arr);
            $sessionId = $arr[0];
            array_shift($arr);
            $toWhoId = $arr[0];
            array_shift($arr);

            $message = implode('', $arr);
            $this->info('================= start ===========');
            // type:1，客服发送；2，游客发送
            if ($type == 1) {
                var_dump($toWhoId);
                $customer = Customer::find($toWhoId);
                $receiverId = $customer->fn_id;
            } else {
                $user = User::find($toWhoId);
                var_dump($userId, $user);
                $receiverId = $user->fn_id;
            }
            var_dump($receiverId, $data, $message);
            $this->info('================= end ===========');
            $ws->push($receiverId, "server: $frame->data  ");
            $ws->push($receiverId, "server: $message  ");


            // 保存聊天记录
            $messageModel = new Message();
            $session = Session::find($sessionId);
            $customerId = $session->customer_id;

            $messageModel->session_id = $sessionId;
            $messageModel->user_id = $userId;
            $messageModel->customer_id = $customerId;
            $messageModel->message = $message;
            $messageModel->save();


            foreach ($request2 as $v) {
                if ($v != $receiverId) {
//                    continue;
                }
//                $ws->push($v, "server: {$frame->data}  $v");
//                $ws->push($frame->fd, "server: {$frame->data}");
            }
        });

        //监听WebSocket连接关闭事件
        $ws->on('close', function ($ws, $fd) {
            echo "client-{$fd} is closed\n";
        });

        $ws->start();
    }
}

