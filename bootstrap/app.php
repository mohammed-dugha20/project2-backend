<?php

use App\Http\Middleware\CustormerMiddleware;
use App\Http\Middleware\FinishingCompanyMiddleware;
use App\Http\Middleware\PlatformAdminMiddleware;
use App\Http\Middleware\RealEstateOfficeMiddleware;
use App\Http\Middleware\ServiceProviderMiddleware;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            HandleCors::class
        ]);
        
        $middleware->validateCsrfTokens(except: [
            '/*',
        ]);
        $middleware->alias([
            'customer' => CustormerMiddleware::class,
            'service_provider' => ServiceProviderMiddleware::class,
            'platform_admin' => PlatformAdminMiddleware::class,
            'finishing_company' => FinishingCompanyMiddleware::class,
            'real_estate_office' => RealEstateOfficeMiddleware::class,
            'admin' => AdminMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
