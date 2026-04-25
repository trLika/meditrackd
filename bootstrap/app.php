<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. Définition des groupes de middleware si nécessaire
        // Le groupe 'web' est déjà configuré par défaut par Laravel

        // 2. Enregistrement des alias pour les middlewares Spatie
        // Cela permet d'utiliser middleware('role:admin') dans tes routes
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\CheckAccountExpiration::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Tu peux ajouter ici une gestion personnalisée si une autorisation est refusée
        // Par exemple, rediriger vers le dashboard si l'utilisateur n'a pas le rôle requis
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->redirectTo('/dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        });
    })->create();
