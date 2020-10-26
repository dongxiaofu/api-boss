<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * Symfony\Component\HttpFoundation\Request
     * @param  \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $code = $exception->getCode();
        if(empty($code)){
            $code = -1;
        }
        $result = [
            'code' => $code,
            'mgs' => $exception->getMessage(),
            'data' => []
        ];

        if ($exception instanceof UnauthorizedHttpException) {
            $httpCode = $exception->getStatusCode();
        } else {
            $httpCode = 200;
        }

        $resultJson = \json_encode($result);
        $headers = [];//$request->headers->all();   // 不能使用客户端发送来的header返回给客户端
        $headers['Content-type'] = 'application/json';
        $response = new Response($resultJson, $httpCode, $headers);
        return $response;
    }
}
