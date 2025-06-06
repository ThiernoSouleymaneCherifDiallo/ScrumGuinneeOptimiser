<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    // ...autres middlewares...

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string|\Closure>
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|\Closure>>
     */
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array<string, class-string|\Closure>
     */
//     protected $routeMiddleware = [
//         'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
//         'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
//         'can' => \Illuminate\Auth\Middleware\Authorize::class,
//         'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
//         'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
//         'role' => \App\Http\Middleware\RoleMiddleware::class, // Custom role middleware
  
  
//     ];
}

