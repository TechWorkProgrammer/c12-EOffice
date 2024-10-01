<?php

namespace App\Exceptions;

use App\Helpers\ResponseHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [
    ];

    protected $dontReport = [
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

        });
    }

    public function render($request, Throwable|HttpExceptionInterface $e): Response|JsonResponse|RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($request->expectsJson()) {
            return $this->handleJsonException($e);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            abort(404);
        }

        if ($this->isHttpException($e)) {
            return $this->renderHttpException($e);
        }

        return parent::render($request, $e);
    }

    protected function handleJsonException(Throwable $e): JsonResponse
    {
        if ($e instanceof AuthenticationException) {
            return ResponseHelper::Unauthorized($e->getMessage());
        } elseif ($e instanceof AuthorizationException) {
            return ResponseHelper::Forbidden($e->getMessage());
        } elseif ($e instanceof NotFoundHttpException) {
            return ResponseHelper::NotFound($e->getMessage());
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return ResponseHelper::MethodNotAllowed($e->getMessage());
        } elseif ($e instanceof ValidationException) {
            return ResponseHelper::BadRequest($e->getMessage());
        } elseif ($e instanceof ModelNotFoundException) {
            return ResponseHelper::NotFound($e->getMessage());
        } elseif ($e instanceof AccessDeniedHttpException) {
            return ResponseHelper::Forbidden($e->getMessage());
        } elseif ($e instanceof ConflictHttpException) {
            return ResponseHelper::Conflict($e->getMessage());
        } elseif ($e instanceof TooManyRequestsHttpException) {
            return ResponseHelper::TooManyRequests($e->getMessage());
        } elseif ($e instanceof UnprocessableEntityHttpException) {
            return ResponseHelper::UnprocessableEntity($e->getMessage());
        } elseif ($e instanceof ServiceUnavailableHttpException) {
            return ResponseHelper::ServiceUnavailable($e->getMessage());
        } elseif ($e instanceof UnauthorizedHttpException) {
            return ResponseHelper::Unauthorized($e->getMessage());
        } elseif ($e instanceof BadRequestHttpException) {
            return ResponseHelper::BadRequest($e->getMessage());
        } elseif ($e instanceof TokenMismatchException) {
            return ResponseHelper::Forbidden($e->getMessage());
        } elseif ($e instanceof QueryException) {
            return ResponseHelper::InternalServerError($e->getMessage());
        } elseif ($e instanceof HttpException) {
            return ResponseHelper::sendResponse($e->getStatusCode(), $e->getMessage());
        } else {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

}
