<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
                  ->withRouting(
                      web     : __DIR__ . '/../routes/web.php',
                      api     : __DIR__ . '/../routes/api.php',
                      commands: __DIR__ . '/../routes/console.php',
                      health  : '/up',
                  )
                  ->withMiddleware(function (Middleware $middleware) {
                      $middleware->alias([
                          'abilities'  => CheckAbilities::class,
                          'ability'    => CheckForAnyAbility::class,
                          'canAtLeast' => \Yajra\Acl\Middleware\CanAtLeastMiddleware::class,
                          'permission' => \Yajra\Acl\Middleware\PermissionMiddleware::class,
                          'role'       => \Yajra\Acl\Middleware\RoleMiddleware::class,
                      ]);

                      $middleware->statefulApi();
                      \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::except([
                          "auth/grantClientCredentials"
                      ]);

                      $middleware->api([
                          \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
                          \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
                          \Illuminate\Routing\Middleware\SubstituteBindings::class,
                      ]);
                  })
                  ->withExceptions(function (Exceptions $exceptions) {
                      $exceptions->render(function (Throwable $e) {
                          if (!request()->ajax() && $e->getMessage() == "Unauthenticated.") {
                              return redirect()
                                  ->route("dashboard")
                                  ->with("error", "Não autorizado");
                          }

                          if (request()->ajax() && $e->getMessage() == "Unauthenticated.") {
                              return response()->json(["error" => "Não autorizado"]);
                          }
                      });
                  })->create();
