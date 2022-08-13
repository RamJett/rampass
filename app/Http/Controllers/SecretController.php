<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Secret;
use App\Models\Maintenance;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SecretController extends Controller
{
  public function __construct()
  {
    $maintenance = Maintenance::where('job', 'expire_secrets')->firstOrCreate([
      'job' => 'expire_secrets',
    ]);
    // increate counter_expire, defaults to 0 and expire_counts defaults to 20
    $maintenance->increment('counter_expire');

    if ($maintenance->expire_counts < $maintenance->counter_expire) {
      $maintenance->update(['counter_expire' => 1]);
      $this->expire_secret_by_expires_at();
    }
  }

  private function expire_secret_by_expires_at()
  {
    $secret = Secret::query()
      ->where('expires_at', '<=', now())
      ->delete();
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    // Not used. Create route is /
    // return redirect()->route('secret.create');
  }

  public function create()
  {
    return Inertia::render('Create', [
      'views' => '5', // how many views
      'units_default' => 'hours',
      'units' => [
        ['name' => 'hours'],
        ['name' => 'minutes'],
        ['name' => 'days'],
      ],
      'time' => '1', // how many 'units'
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $uuid = Str::uuid();

    if ($request->units == 'days') {
      $datetime = Carbon::now()->addDays($request->time);
    } elseif ($request->units == 'hours') {
      $datetime = Carbon::now()->addHours($request->time);
    } else {
      $datetime = Carbon::now()->addMinutes($request->time);
    }

    $request->merge([
      'content_type' => Str::lower($request->header('Content-Type')),
      'date_expires' => $datetime,
    ]);

    $validator = $request->validate([
      'date_expires' =>
        'required|date|before_or_equal:' . Carbon::now()->addMonth(),
      'content_type' => 'required|starts_with:application/json',
      'time' => 'required|integer|gte:1',
      'views' => 'required|integer|gte:1',
      'units' => 'required|in:minutes,hours,days',
      'secret' => 'required',
    ]);

    $expire_views = $request->views;

    $secret = Secret::create([
      'secret' => Crypt::encryptString($request->secret),
      'uuid' => crypt($uuid, '$6$rounds=5000$' . env('APP_SALT') . '$'),
      'expires_at' => $datetime,
      'expire_views' => $expire_views,
    ]);

    $expires_at = $secret->expires_at;
    $views_remaining = $secret->expire_views - $secret->count_views;

    return Inertia::render('Store', [
      'expires_at' => $expires_at,
      'url' => env('APP_URL') . '/' . $uuid,
      'views_remaining' => $views_remaining,
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  string  $uuid
   * @return \Illuminate\Http\Response
   */
  public function show($uuid)
  {
    $input = ['uuid' => $uuid];
    $validator = Validator::make($input, [
      'uuid' => 'uuid',
    ]);

    if ($validator->fails()) {
      abort(404);
    }

    $secret = Secret::where(
      'uuid',
      crypt($uuid, '$6$rounds=5000$' . env('APP_SALT') . '$')
    )->firstOrFail();
    if (!empty($secret->secret)) {
      // Check for expiry
      if (
        Carbon::now()->gte($secret->expires_at) ||
        $secret->count_views >= $secret->expire_views
      ) {
        $secret->delete();
        return abort(404);
      } else {
        // Increment the view count
        $secret->increment('count_views');

        $secretDecrypted = Crypt::decryptString($secret->secret);
        $expires_at = $secret->expires_at;
        $views_remaining = $secret->expire_views - $secret->count_views;
      }
    } else {
      return abort(404);
    }

    return Inertia::render('Show', [
      'secret' => $secretDecrypted,
      'expires_at' => $expires_at,
      'views_remaining' => $views_remaining,
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $uuid
   * @return \Illuminate\Http\Response
   */
  public function destroy($uuid)
  {
    $input = ['uuid' => $uuid];
    $validator = Validator::make($input, [
      'uuid' => 'uuid',
    ]);

    if ($validator->fails()) {
      abort(404);
    }

    $secret = Secret::where(
      'uuid',
      crypt($uuid, '$6$rounds=5000$' . env('APP_SALT') . '$')
    )->firstOrFail();

    if (!empty($secret->uuid)) {
      $secret->delete();
    }
  }
}
