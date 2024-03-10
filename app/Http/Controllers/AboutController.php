<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutController extends Controller
{
  public function index()
  {
    return Inertia::render('About');
  }

  public function togithub()
  {
    return Inertia::location("https://github.com/RamJett/rampass");
  }
}
