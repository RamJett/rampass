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
      'now' => Carbon::now()->addHour()
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
    //$timeString = $request->input('expires_date') . ' ' . $request->input('expires_time');
    //$datetime = Carbon::createFromFormat('Y-m-d H:i', $timeString);
    //$datetime = $datetime->subHours($request->input('utc_offset'));
    // placeholders
    $datetime = Carbon::now()->addHour();
    $expire_views = 5;

    $secret = Secret::create(array(
      'secret' => Crypt::encryptString($request->input('secret')),
      'uuid' => crypt($uuid, '$6$rounds=5000$' . env('APP_SALT') . '$'),
      'expires_at' => $datetime,
      'expire_views' => $expire_views
    ));

    $expires_at = $secret->expires_at;
    $views_remaining = $secret->expires_views - $secret->count_views;

    return Inertia::render('Store', [
      'expires_at' => $expires_at,
      'url' => env('APP_URL') . "/" . $uuid,
      'views_remaining' => $views_remaining
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $uuid
   * @return \Illuminate\Http\Response
   */
  public function show($uuid)
  {
    $items = Secret::all()->filter(function($record) use($uuid) {
    if(Crypt::decrypt($record->nric) == $nric) {
        return $record;
    }
    $encrypted = Crypt::encryptString($uuid);
    $secret = Secret::where('uuid', '=', $encrypted)->first();
    dd($secret);

    return Inertia::render('Show', [
      'secret' => $secret
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
