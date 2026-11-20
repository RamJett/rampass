<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        // never write a submitted secret to the session on a rejected form
        'secret',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // API validation errors in the API's own format
        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->is('api')) {
                return response()->json([
                    'status' => 'fail',
                    'errors' => $e->validator->errors()->all(),
                ], 422);
            }
        });
    }
}
