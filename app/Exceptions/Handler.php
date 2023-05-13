<?php

namespace App\Exceptions;

use App\Http\Responses\JsonApiValidationErrorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $this->renderable(function (NotFoundHttpException $e) {
            throw new JsonApi\NotFoundHttpException;
        });
        $this->renderable(function (BadRequestHttpException $e) {
            throw new JsonApi\BadRequestHttpException($e->getMessage());
        });
        $this->renderable(function (AuthenticationException $e) {
            throw new JsonApi\AuthenticationException($e->getMessage());
        });
    }

    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        if (! $request->routeIs('api.v1.login')) {
            return new JsonApiValidationErrorResponse($exception);
        }

        return parent::invalidJson($request, $exception);
    }
}
