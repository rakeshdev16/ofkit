<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Handle TokenMismatchException (419 Page Expired)
        // if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
        //     // // Check if user is authenticated
        //     // if (auth()->check()) {
        //     //     // If authenticated, redirect to homepage
        //     //     return redirect()->back();
        //     // } else {
        //     //     // If not authenticated, redirect to login page
        //     //     return redirect()->route('login');
        //     // }
        // }

        return parent::render($request, $exception);
    }
}
