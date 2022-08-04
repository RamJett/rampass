<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
  use HasFactory;

  protected $fillable = array('uuid', 'created_at', 'expires_at', 'expire_views', 'secret');
}
