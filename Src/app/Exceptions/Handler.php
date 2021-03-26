<?php

namespace App\Exceptions;

use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, $e)
    {
        // if user'session has expired then redirect to login page
        if ($e instanceof TokenMismatchException) {
            return redirect()->route('login');
        }

        // method is not supported for this route
        if ($e instanceof MethodNotAllowedHttpException) {
            return redirect()->route('login');
        } 

        // 404 - 403
        if($this->isHttpException($e)){
            switch ($e->getStatusCode()) {
                case 404:
                    if(!empty($e->getMessage())){
                        return redirect()->route('404')->with('msg-abort',$e->getMessage());
                    }
                    return redirect()->route('404');
                    break;
                case 403:
                    if (!empty($e->getMessage())) {
                        return redirect()->route('403')->with('msg-abort', $e->getMessage());
                    }
                    return redirect()->route('403');
                    break;
            }
        }

        return parent::render($request, $e);
    }
}
