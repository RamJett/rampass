<?php

/*
| Limits for new secrets (web form and API). Override in .env.
*/
return [
  // Longest a secret may live, in days
  'max_days' => (int) env('RAMPASS_MAX_DAYS', 31),

  // Most views a secret may allow
  'max_views' => (int) env('RAMPASS_MAX_VIEWS', 100),

  // Longest secret, in characters
  'max_secret_length' => (int) env('RAMPASS_MAX_SECRET_LENGTH', 10000),

  // New secrets per minute from one IP (web form and API)
  'create_per_minute' => (int) env('RAMPASS_CREATE_PER_MINUTE', 10),
];
