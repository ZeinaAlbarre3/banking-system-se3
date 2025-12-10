<?php

use App\Exceptions\Types\CustomException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Exception\RfcComplianceException;
use Illuminate\Validation\ValidationException;

return [

    ModelNotFoundException::class => 'handleModelNotFoundException',

    NotFoundHttpException::class => 'handleNotFoundHttpException',

    AuthenticationException::class => 'handleAuthenticationException',

    QueryException::class => 'handleQueryException',

    PostTooLargeException::class => 'handlePostTooLargeException',

    MethodNotAllowedHttpException::class => 'handleMethodNotAllowedHttpException',

    TooManyRequestsHttpException::class => 'handleTooManyRequestsHttpException',

    AuthorizationException::class => 'handleAuthorizationException',

    TransportException::class => 'handleTransportException',

    TransportExceptionInterface::class => 'handleTransportExceptionInterface',

    RfcComplianceException::class => 'handleRfcComplianceException',

    ValidationException::class => 'handleValidationException',

    CustomException::class => 'handleCustomException',

];
