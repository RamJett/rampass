<?php

namespace App\Http\Controllers\Concerns;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Shared checks for a new secret, used by the web form and the API, so both
 * enforce the same limits (config/rampass.php).
 */
trait ValidatesNewSecret
{
  /**
   * Validate the request and return when the secret expires.
   */
  protected function validateNewSecret(Request $request): Carbon
  {
    $maxDays = config('rampass.max_days');

    $request->validate([
      // Bounded first, so the expiry date below cannot overflow
      'time' => 'required|integer|min:1|max:' . $maxDays * 24 * 60,
      'units' => 'required|in:minutes,hours,days',
      'views' => 'required|integer|min:1|max:' . config('rampass.max_views'),
      'secret' => 'required|string|max:' . config('rampass.max_secret_length'),
    ]);

    $time = (int) $request->time;
    $expires = match ($request->units) {
      'days' => Carbon::now()->addDays($time),
      'hours' => Carbon::now()->addHours($time),
      default => Carbon::now()->addMinutes($time),
    };

    if ($expires->gt(Carbon::now()->addDays($maxDays))) {
      throw ValidationException::withMessages([
        'time' => "A secret can live at most {$maxDays} days.",
      ]);
    }

    return $expires;
  }
}
