<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Secret;
use App\Models\Maintenance;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SecretController extends Controller
{
  public function store(Request $request)
  {
    // TODO: validation

    $uuid = Str::uuid();
    $now = Carbon::now();

    if ($request->units == 'minutes') {
      $datetime = $now->addMinutes($request->time);
    } elseif ($request->units == 'hours') {
      $datetime = $now->addHours($request->time);
    } else {
      $datetime = $now->addDays($request->time);
    }

    $expire_views = $request->views;

    $secret = Secret::create([
      'secret' => Crypt::encryptString($request->secret),
      'uuid' => crypt($uuid, '$6$rounds=5000$' . env('APP_SALT') . '$'),
      'expires_at' => $datetime,
      'expire_views' => $expire_views,
    ]);

    $expires_at = $secret->expires_at;
    $views_remaining = $secret->expires_views - $secret->count_views;

    return 'Testing';
  }
}
