<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Secret;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SecretController extends Controller
{
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return redirect()->route('secret.create');
  }

  public function create()
  {
    return Inertia::render('Create', [
      'views' => 5, // how many views
      'units_default' => 'hours',
      'units' => [
        ['name' => 'hours'],
        ['name' => 'minutes'],
        ['name' => 'days'],
      ],
      'time' => 1, // how many 'units'
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
    // TODO: validation

    $uuid = Str::uuid();
    $now = Carbon::now();

    if ($request->units == 'minutes') {
      $datetime = Carbon::now()->addMinutes($request->time);
    } elseif ($request->units == 'hours') {
      $datetime = Carbon::now()->addHours($request->time);
    } else {
      $datetime = Carbon::now()->addDays($request->time);
    }

    $expire_views = $request->views;

    $secret = Secret::create([
      'secret' => Crypt::encryptString($request->input('secret')),
      'uuid' => crypt($uuid, '$6$rounds=5000$' . env('APP_SALT') . '$'),
      'expires_at' => $datetime,
      'expire_views' => $expire_views,
    ]);

    $expires_at = $secret->expires_at;
    $views_remaining = $secret->expires_views - $secret->count_views;

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
    $secret = Secret::where(
      'uuid',
      crypt($uuid, '$6$rounds=5000$' . env('APP_SALT') . '$')
    )->firstOrFail();
    if (!empty($secret)) {
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

        // Get attributes for display
        $secretDecrypted = Crypt::decryptString($secret->secret);
        $expires_at = $secret->expires_at;
        $views_remaining = $secret->expire_views - $secret->count_views;
      }
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
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($uuid)
  {
    //
  }
}
