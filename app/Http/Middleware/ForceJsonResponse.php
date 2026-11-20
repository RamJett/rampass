<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * API clients (eqpress scripts) send no Accept header; without this a
 * validation error came back as an HTML redirect instead of JSON.
 */
class ForceJsonResponse
{
  public function handle(Request $request, Closure $next): Response
  {
    $request->headers->set('Accept', 'application/json');

    return $next($request);
  }
}
