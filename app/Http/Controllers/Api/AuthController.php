<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\JobHunter;
use App\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth:api');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $email = $request->get('name');
        $password = $request->get('password');
        $user = User::where('name', $email)
            ->first();

        $result = [
            'msg' => 'Unauthorized',
            'code' => 1,
            'data' => []
        ];

        if (is_null($user)) {
            return $this->response($result, 401);
        }
        if (!Hash::check($password, $user->password)) {
            return $this->response($result, 401);
        }
        $token = auth('api')->login($user);
        // 把token保存到用户数据中
        if($user && $token){
            $user->token = $token;
            $user->save();
        }


        return $this->respondWithToken($token, $user);
    }

    public function register(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $user = User::where('email', $email)->first();
        if ($user) {

            $result = [
                'data' => [],
                'msg' => '该账户已经注册过了',
                'code' => 2
            ];

            return $this->response($result);
        }

        $user = User::create([
            // 默认姓名是email
            'name' => $email,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

//        var_dump($user);

        if (is_null($user)) {
            $result = [
                'data' => [],
                'msg' => '注册失败',
                'code' => 3
            ];

            return $this->response($result);
        }

        $jobHunter = JobHunter::create([
            'user_id' => $user->id,
            'email' => $email,
            'advantage' => '',
        ]);
//        var_dump($jobHunter);

        $token = auth('api')->login($user);
        // 提供更全面的用户信息
        $user = User::find($user->id);

        return $this->respondWithToken($token, $user);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $result = [
            'code' => 0,
            'data' => ['user' => auth('api')->user()],
            'msg' => ''
        ];

        return $this->response($result);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        $result = [
            'code' => 0,
            'data' => [],
            'msg' => 'Successfully logged out'
        ];

        return $this->response($result);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return $this->respondWithToken(auth('api')->refresh());
        } catch (\Exception $exception) {
            $data = [
                'code' => $exception->getCode(),
                'data' => [],
                'msg' => $exception->getMessage()
            ];
            return $this->response($data);
        }

    }

    public function test(Request $request)
    {
        try {
            $result = [
                'data' => [__FILE__],
                'code' => 5,
                'msg' => 'test'
            ];
        } catch (\Exception $exception) {
            $result = [
                'data' => [$exception->getMessage()],
                'code' => $exception->getCode(),
                'msg' => $exception->getMessage()
            ];
        }

        return $this->response($result);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        $data = [
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => $user
            ],
            'code' => 0,
            'msg' => ''
        ];
        return $this->response($data);
    }
}
