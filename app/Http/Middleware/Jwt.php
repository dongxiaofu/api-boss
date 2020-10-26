<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Facades\Response;
use JWTAuth;
use App\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Jwt extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        // 检查此次请求中是否带有 token，如果没有则抛出异常。
        $this->checkForToken($request);
        // 检测用户的登录状态，如果正常则通过
        if ($this->auth->parseToken()->authenticate()) {
            return $next($request);
        }
        throw new UnauthorizedHttpException('jwt-auth', '未登录');
    }
}
