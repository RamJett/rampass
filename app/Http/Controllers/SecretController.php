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
    // This is create() since this is only option. We do not display a listing.

    return Inertia::render('Index');
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
      'uuid' => Crypt::encryptString($uuid),
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
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
