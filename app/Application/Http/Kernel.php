<?php

namespace App\Application\Http;

use App\Application\Http\Middleware\EncryptCookies;
use App\Application\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Application\Http\Middleware\RedirectIfAuthenticated;
use App\Application\Http\Middleware\TrimStrings;
use App\Application\Http\Middleware\TrustProxies;
use App\Application\Http\Middleware\VerifyCsrfToken;
use App\Domains\Common\Http\Middleware\AddTimestamp;
use App\Domains\Common\Http\Middleware\AddUserToSentryScope;
use App\Domains\Common\Http\Middleware\ForceJsonResponse;
use App\Domains\Common\Http\Middleware\Recaptcha;
use Fruitcake\Cors\HandleCors;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint
     */
    protected $middleware = [
        // \App\Application\Http\Middleware\TrustHosts::class,
        TrustProxies::class,
        HandleCors::class,
        PreventRequestsDuringMaintenance::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            'sentry.user:admin',
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            ForceJsonResponse::class,
            AddTimestamp::class,
            SubstituteBindings::class,
            'sentry.user:sanctum',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint
     */
    protected $routeMiddleware = [
        'auth' => \App\Application\Http\Middleware\Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'password.confirm' => RequirePassword::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequestsWithRedis::class,
        'verified' => EnsureEmailIsVerified::class,
        'recaptcha' => Recaptcha::class,
        'sentry.user' => AddUserToSentryScope::class,
    ];

    protected $middlewarePriority = [
        ForceJsonResponse::class,
        Authenticate::class,
        SubstituteBindings::class,
        Authorize::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
        SetCacheHeaders::class,
        AddTimestamp::class,
        AddUserToSentryScope::class,
    ];
}
