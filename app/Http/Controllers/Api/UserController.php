<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Service\JobHunterService;
use App\Service\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


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

    // 修改客服资料
    public function saveUser(Request $request)
    {
        $userId = trim($request->get('user_id', 0));
        $avatar = trim($request->get('new_avatar', ''));
        $pwd = trim($request->get('new_pwd', ''));
        $oldPwd = trim($request->get('old_pwd', ''));
        if (empty($userId) || empty($avatar) || empty($pwd) || empty($oldPwd)) {
            $result = [
                'code' => -1,
                'msg' => '参数不正确',
                'data' => []
            ];
            return $this->response($result);
        }

        try {
            $avatarUrl = $this->saveAvatar($avatar);
            $this->updateUser($userId, $avatarUrl, $pwd, $oldPwd);
        } catch (\Exception $exception) {
            $result = [
                'code' => -1,
                'msg' => $exception->getMessage(),
                'data' => []
            ];
        }


        return $this->response($result);
    }

    private function saveAvatar($avatar)
    {
        $avatarUrl = Utils::base64_image_content($avatar, Utils::IMAGE_PATH);

        return $avatarUrl;
    }

    private function updateUser($userId, $avatarUrl, $password, $oldPwd)
    {
        $user = User::find($userId);

        if (empty($user)) {
            throw new \Exception('用户不存在', -1);
        }

        if ($user->password != Hash::make($oldPwd)) {
            throw new \Exception('旧密码填写错误', -1);
        }

        $user->avatar = $avatarUrl;
        $user->password = Hash::make($password);
        $user->save();
    }

}
