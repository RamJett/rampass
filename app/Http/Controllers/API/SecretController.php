<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Secret;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SecretController extends Controller
{
  public function store(Request $request)
  {
    $uuid = Str::uuid();

    if ($request->units == 'minutes') {
      $datetime = Carbon::now()->addMinutes($request->time);
    } elseif ($request->units == 'hours') {
      $datetime = Carbon::now()->addHours($request->time);
    } else {
      $datetime = Carbon::now()->addDays($request->time);
    }
    // First make sure content-type is application/json
    $request->merge([
      'content_type' => Str::lower($request->header('Content-Type')),
    ]);

    $validator = Validator::make($request->all(), [
      'content_type' => 'required|starts_with:application/json',
      'time' => 'required|integer|gte:1',
      'views' => 'required|integer|gte:1',
      'units' => 'required|in:minutes,hours,days',
      'secret' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 'fail',
          'errors' => $validator->errors()->all(),
        ],
        422
      );
    }

    $expire_views = $request->views;

    $secret = Secret::create([
      'secret' => Crypt::encryptString($request->secret),
      'uuid' => crypt($uuid, '$6$rounds=5000$' . env('APP_SALT') . '$'),
      'expires_at' => $datetime,
      'expire_views' => $expire_views,
    ]);

    $expires_at = $secret->expires_at;
    $views_remaining = $secret->expire_views - $secret->count_views;

    return response()->json([
      'status' => 'success',
      'data' => [
        'url' => env('APP_URL') . '/' . $uuid,
        'api_url' => env('APP_URL') . '/api/' . $uuid,
        'expires_at' => $expires_at,
        'views_remaining' => $views_remaining,
      ],
    ]);
  }

  public function show($uuid)
  {
    //TODO: validation on $uuid

    $secret = Secret::where(
      'uuid',
      crypt($uuid, '$6$rounds=5000$' . env('APP_SALT') . '$')
    )->first();

    if (!empty($secret->secret)) {
      // Check for expiry
      if (
        Carbon::now()->gte($secret->expires_at) ||
        $secret->count_views >= $secret->expire_views
      ) {
        $secret->delete();
        return response()->json(
          [
            'status' => 'fail',
          ],
          404
        );
      } else {
        // Increment the view count
        $secret->increment('count_views');

        $secretDecrypted = Crypt::decryptString($secret->secret);
        $expires_at = $secret->expires_at;
        $views_remaining = $secret->expire_views - $secret->count_views;
      }
    } else {
      return response()->json(
        [
          'status' => 'fail',
        ],
        404
      );
    }

    return response()->json([
      'status' => 'success',
      'data' => [
        'secret' => $secretDecrypted,
        'expires_at' => $expires_at,
        'views_remaining' => $views_remaining,
      ],
    ]);
  }
}
