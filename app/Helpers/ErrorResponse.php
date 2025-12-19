<?php

use App\Exceptions\Types\CustomException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Exception\RfcComplianceException;
use App\Helpers\ExceptionResponder;


function handleModelNotFoundException(ModelNotFoundException $e): JsonResponse
{
    return ExceptionResponder::Error([],"Model not Found: ".$e->getMessage(), 404);
}

function handleNotFoundHttpException(NotFoundHttpException $e): JsonResponse
{
    return ExceptionResponder::Error([],"Not Found: ".$e->getMessage(), 404);
}

function handleAuthenticationException(AuthenticationException $e): JsonResponse
{
    return ExceptionResponder::Error([],"Authentication failed: ".$e->getMessage(), 401);
}

function handleAuthorizationException(AuthorizationException $e): JsonResponse
{
    return ExceptionResponder::Error([],"Authorization failed: ".$e->getMessage(), 403);
}

function handleQueryException(QueryException $e): JsonResponse
{
    return ExceptionResponder::Error([],"Query failed: ".$e->getMessage(), 500);
}

function handlePostTooLargeException(PostTooLargeException $e): JsonResponse
{
    return ExceptionResponder::Error([],"Post too large: ".$e->getMessage(), 413);
}

function handleValidationException(ValidationException $e): JsonResponse
{
    return ExceptionResponder::Validation([], $e->errors());
}

function handleMethodNotAllowedHttpException(MethodNotAllowedHttpException $e): JsonResponse
{
    return ExceptionResponder::Error([],"Method not allowed: ".$e->getMessage(), 405);
}

function handleTooManyRequestsHttpException(TooManyRequestsHttpException $e): JsonResponse
{
    return ExceptionResponder::Error([],"Too many requests: ".$e->getMessage(), 429);
}

function handleTransportException(TransportException $e): JsonResponse
{
    return ExceptionResponder::Error([],"Transport exception: ".$e->getMessage(), 502);
}

function handleTransportExceptionInterface(TransportExceptionInterface $e): JsonResponse
{
    return ExceptionResponder::Error([],"Transport exception interface: ".$e->getMessage(), 502);
}

function handleRfcComplianceException(RfcComplianceException $e): JsonResponse
{
    return ExceptionResponder::Error([],$e->getMessage(), 422);
}

function handleCustomException(CustomException $e): JsonResponse
{
    return ExceptionResponder::Error([],$e->getMessage(), $e->getStatusCode());
}

