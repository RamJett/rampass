<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Concerns\ValidatesNewSecret;
use App\Models\Secret;
use App\Models\Maintenance;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SecretController extends Controller
{
  use ValidatesNewSecret;

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

    $datetime = $this->validateNewSecret($request);

    $request->merge([
      'content_type' => Str::lower($request->header('Content-Type')),
    ]);
    $request->validate([
      'content_type' => 'required|starts_with:application/json',
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
  /**
   * Find a secret that can still be viewed, or null. Expired or used up
   * secrets are deleted on the way.
   */
  private function liveSecret($uuid)
  {
    if (Validator::make(['uuid' => $uuid], ['uuid' => 'uuid'])->fails()) {
      return null;
    }

    $secret = Secret::where(
      'uuid',
      crypt($uuid, '$6$rounds=5000$' . env('APP_SALT') . '$')
    )->first();

    if (!$secret || empty($secret->secret)) {
      return null;
    }

    if (
      Carbon::now()->gte($secret->expires_at) ||
      $secret->count_views >= $secret->expire_views
    ) {
      $secret->delete();
      return null;
    }

    return $secret;
  }

  /**
   * The link page. It does not count a view and does not contain the
   * secret, so email scanners and chat link previews that open the link
   * cannot use it up. The secret is shown by reveal(), on a button press.
   */
  public function show($uuid)
  {
    $secret = $this->liveSecret($uuid);
    if (!$secret) {
      abort(404);
    }

    return Inertia::render('Show', [
      'uuid' => $uuid,
      'expires_at' => $secret->expires_at,
      'views_remaining' => $secret->expire_views - $secret->count_views,
    ]);
  }

  /**
   * Count one view and return the secret (POST from the link page).
   */
  public function reveal($uuid)
  {
    $secret = $this->liveSecret($uuid);
    if (!$secret) {
      return response()->json(['status' => 'fail'], 404);
    }

    $secret->increment('count_views');

    return response()
      ->json([
        'status' => 'success',
        'data' => [
          'secret' => Crypt::decryptString($secret->secret),
          'expires_at' => $secret->expires_at,
          'views_remaining' => $secret->expire_views - $secret->count_views,
        ],
      ])
      ->header('Cache-Control', 'no-store');
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

    return redirect()
      ->route('secret.create')
      ->with('notification', 'Successfully deleted secret.');
  }
}
