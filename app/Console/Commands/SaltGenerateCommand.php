<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SaltGenerateCommand extends Command
{

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'salt:generate
                    {--show : Display the key instead of modifying files}
                    {--force : Force the operation to run when in production}';

  /**
   * The name of the console command.
   *
   * This name is used to identify the command during lazy loading.
   *
   * @var string|null
   *
   * @deprecated
   */
  protected static $defaultName = 'salt:generate';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Set the application salt';

  /**
   * Execute the console command.
   *
   * @return void
   */
  public function handle()
  {

    $salt = $this->getRandomSalt();

    if ($this->option('show')) {
      return $this->line('<comment>' . $salt . '</comment>');
    }

    if (!$this->setSaltInEnvironmentFile($salt)) {
      return;
    }

    $this->laravel['config']['app.salt'] = $salt;

    $this->components->info('Application salt set successfully.');
  }
  /**
   * Generate a random salt for the application.
   *
   * @return string
   */
  protected function getRandomSalt()
  {
    return Str::random(32);
  }

  protected function setSaltInEnvironmentFile($salt)
  {
    $currentSalt = $this->laravel['config']['app.salt'];

    if (strlen($currentSalt) !== 0 && (!$this->confirmToProceed())) {
      return false;
    }

    $this->writeNewEnvironmentFileWith($salt);

    return true;
  }

  /**
   * Write a new environment file with the given salt.
   *
   * @param  string  $salt
   * @return void
   */
  protected function writeNewEnvironmentFileWith($salt)
  {
    file_put_contents($this->laravel->environmentFilePath(), preg_replace(
      $this->SaltReplacementPattern(),
      'APP_SALT=' . $salt,
      file_get_contents($this->laravel->environmentFilePath())
    ));
  }
  /**
   * Get a regex pattern that will match env APP_SALT with any random key.
   *
   * @return string
   */
  protected function SaltReplacementPattern()
  {
    $escaped = preg_quote('=' . $this->laravel['config']['app.salt'], '/');

    return "/^APP_SALT{$escaped}/m";
  }
}
