<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Session\TokenMismatchException;
use App\Http\Middleware\EnsureCorrectMinistry;
use App\Http\Middleware\SeeMinistry;
use App\Http\Middleware\RedirectFromMinistryDashboard;
use App\Http\Middleware\RedirectFromEvent;
use App\Http\Middleware\RedirectFromChurch;
use App\Http\Middleware\SetLocale;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
        'ensure.ministry' => EnsureCorrectMinistry::class,
        'see.ministry' => SeeMinistry::class,
        'redirect.dashboard' => RedirectFromMinistryDashboard::class,
        'redirect.event' => RedirectFromEvent::class,
        'redirect.church' => RedirectFromChurch::class,
        'set.locale' => SetLocale::class,
        ]);
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (TokenMismatchException $e, $request) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Your session expired. Please try submitting the form again.');
        });
        $exceptions->render(function (InvalidSignatureException $e, $request) {
            return response()->view('errors.link-expired', [], 403);
        });
    })->create();