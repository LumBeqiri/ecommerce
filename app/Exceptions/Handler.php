<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
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
        $this->reportable(function (Throwable $e) {
            //
        });
}

    public function render($request, Throwable $exception)
    {
        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception,$request);
        }

        if($exception instanceof ModelNotFoundException){
            return $this->errorResponse("Does not exists any model  with the speicified indentificator",404);
        }

        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage(), 403);
        }

        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse("The specified method for the request is invalid", 405);
        }

        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse("The specified url cannot be found ", 404);
        }

        // ATTENTION
        // exception->getSatusCode instead of getCode() 
        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if($exception instanceof QueryException){
           $errorCode = $exception->errorInfo[1];

           if($errorCode == 1451){
               return $this->errorResponse('Cannot remove this resource permanently. It is related with some other resource', 409);
           }
        }

        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());
           // return redirect()->route('login');
        }


        if(config('app.debug')){
          return parent::render($request, $exception);
        }
        
        return $this->errorResponse('Unexpected Exception. Try later', 500);



    }



    protected function unauthenticated($request, AuthenticationException $exception){
        
        if($this->isFrontend($request)){
            return redirect()->guest('login');
        }

        return $this->errorResponse('Unauthenticated',401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors =  $e->validator->errors()->getMessages();

        if($this->isFrontend($request)){
            return $request->ajax() ? response()->json($errors, 422) : back()
            ->withInput($request->input())
            ->withErrors($errors); 
        }
    }

    public function isFrontend($request){
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}