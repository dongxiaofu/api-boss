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
use itbdw\Ip\IpLocation;

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
    protected $logTag = 'web-socket';

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


        //监听WebSocket连接打开事件
        $ws->on('open', function ($ws, $request) {
            $getData = $request->get;
            $type = intval($getData['type'] ?? 0);
            $userId = intval($getData['userId'] ?? 0);
            $customerId = intval($getData['customerId'] ?? 0);
            $sessionId = intval($getData['sessionId'] ?? 0);
            $ip = $getData['ip'] ?? 0;
            $requestId = $request->fd;
            $token = $getData['token'] ?? '';
            if ($token) {
                $this->info('用户 ' . $userId . ' 登录,fd是 ' . $requestId);
            }
            $token = trim($token);

            if ($type == 1) {
                $user = User::find($userId);

                // 单用户登录
                $oldToken = $user->token ?? '';
                $this->info('old:' . $oldToken);
                $this->info('new:' . $token);
                $fd = $user->fn_id;
                // 这个错误的逻辑，消耗了非常非常多时间。
//                if ($token && $oldToken && md5($oldToken) != md5($token)) {
                // 登录，而且，新fd与数据库中的fd不一致，则执行挤下线操作。
                // 新旧fd一定不一致。
                // 使用token比较，是不行的。在登录时，旧token已经被替换了。
                // 在心跳机制中执行挤下线操作，更合适。
                if ($token && $requestId != $fd) {
                    // 通知$userId的另一个客户端下线
                    $msg = '88|' . $fd . '|' . $userId . '|' . $sessionId . '|' . $customerId;
                    $this->info('退出登录 start2===========' . time());
                    $this->info($msg);
                    $this->info('退出登录 end2===========');
                    $ws->push($fd, $msg);
                }


                $user->fn_id = $requestId;
                $token && $user->token = $token;
                $user->save();

                $sessionId = 0;
                $customerId = 0;
            } else {
                $address = $this->getAddressByIP($ip);
                // 同一个游客，复用账号和会话
                if ($customerId) {
                    $customer = Customer::find($customerId);
                    $customer->address = $address;
                    $customer->fn_id = $requestId;
                    $customer->save();
                } else {
                    // 创建游客账号
                    $customer = new Customer();
                    $customer->name = '游客' . strval(mt_rand(1, 200));
                    $customer->address = $address;
                    $customer->is_block = 1;
                    $customer->fn_id = $requestId;
                    $customer->save();
                    $customerId = $customer->id;
                }

                if ($sessionId) {
                    // 更新会话
//                    $session = Session::find($sessionId)->where('user_id', $userId)->where('customer_id', $customerId);
                    $session = Session::find($sessionId);
                    $this->info('session start====================');
                    if ($session) {
                        $session->date_text = date('Ymd');
                        $session->address = $address;
                        $session->save();
                    }
                    $this->info('session end====================');
                } else {
                    // 创建会话
                    $session = new Session();
                    $session->user_id = $userId;
                    $session->customer_id = $customerId;
                    $session->date_text = date('Ymd');
                    $session->address = $address;
                    $session->status = 1;
                    $session->is_block = 1;
                    $session->save();
                    $sessionId = $session->id;
                    $this->info('创建游客账号 start');
                    $this->info('创建游客账号 end');
                }

                // 向客服推送一条信息，开启一个新的会话
                $user = User::find($userId);
                $receiverId = $user->fn_id;
                $msg = '2|' . $requestId . '|' . $userId . '|' . $sessionId . '|' . $customerId;
                $ws->push($receiverId, $msg);
            }

            $requestJson = Cache::get('request2');
            if (is_null($requestJson)) {
                $request2[] = $requestId;
            } else {
                $request2 = \json_decode($requestJson, true);
                $request2[] = $requestId;
            }
            $requestJson = \json_encode($request2);
            Cache::put('request2', $requestJson);
            // todo 在最后加了游客ID
            $msg = '-1|' . $requestId . '|' . $userId . '|' . $sessionId . '|' . $customerId;
            $this->info('msg start2===========');
            $this->info($msg);
            $this->info('msg end2===========');
            $ws->push($request->fd, $msg);
        });

        //监听WebSocket消息事件
        $ws->on('message', function ($ws, $frame) {
//            // var_dump($frame);

//            $user = User::find(1);
//            $receiverId = $user->fn_id;
//            $ws->push($receiverId, $frame->data);
//
//            return;
            $requestJson = Cache::get('request2');
            if (is_null($requestJson)) {
                return;
            }
//            // var_dump($requestJson);
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
//            $this->info('arr start=============');
//            // var_dump($arr);
//            $this->info('arr end=============');
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
                $this->info('toWhoId:' . $toWhoId);
                $customer = Customer::find($toWhoId);
                $isBlock = $customer->is_block ?? 0;
                // var_dump($customer);
                $receiverId = $customer->fn_id ?? 0;
                // var_dump($receiverId);
            } else {
                $this->info('================= $toWhoId s===========');
                $this->info($toWhoId);
                $this->info('================= $toWhoId e===========');
                $user = User::find($toWhoId);
                $receiverId = $user->fn_id;
            }
            $this->info('================= end ===========');
            $msgData = $arr[count($arr) - 2];
            $msgType = $arr[count($arr) - 1];
            if ($msgType == 1 || $msgType == 2) {
                $msgData = $msgData . '|' . $msgType;
            } elseif ($msgType == 3) {
                $filename = '/Users/cg/data/www/boss-api-fix/app/Console/Commands/' . time() . '.png';
                file_put_contents($filename, $msgData);
                $filePath = '/Users/cg/data/www/cg/html/ws/pic';
                $msgData = $this->base64_image_content($msgData, $filePath);
                $msgData = 'pic/' . $msgData . '|' . $msgType;
            }

            // 被屏蔽的会话的消息，不转发。用session作为判断依据，简洁.
            // 游客发来的消息，没有包含游客ID
            $session = Session::find($sessionId);
            $isBlock = $session->is_block ?? 0;
            if ($isBlock == 1 && $receiverId) {
                $ws->push($receiverId, $msgData);
                // 保存聊天记录
                $messageModel = new Message();
                $customerId = $session->customer_id;
                $messageModel->session_id = $sessionId;
                $messageModel->user_id = $userId;
                $messageModel->customer_id = $customerId;
                $messageModel->message = $msgData;
                $messageModel->date_text = date('Ymd');
                $messageModel->status = 1;
                $messageModel->save();
            }
        });

        //监听WebSocket连接关闭事件
        $ws->on('close', function ($ws, $fd) {
            echo "client-{$fd} is closed\n";
        });

        $ws->start();
    }

    private function base64_image_content($base64_image_content, $path)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $childPath = date('Ymd', time()) . "/";
            $new_file = $path . "/" . $childPath;
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $filename = time() . ".{$type}";
            $new_file = $new_file . $filename;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return $childPath . '/' . $filename;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function getAddressByIP($ip)
    {
        $unknownAddress = '未知地址';
        $qqwry_filepath = $path = base_path('vendor/itbdw/ip-database/src/qqwry.dat');
        $addressInfo = json_encode(IpLocation::getLocation($ip, $qqwry_filepath), JSON_UNESCAPED_UNICODE);
        if (!$addressInfo) {
            return $unknownAddress;
        } else {
            return $addressInfo['area'] ?? $unknownAddress;
        }
    }
}

