<?php

namespace jCube\Providers;

use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
  public function boot(): void
  {
    $this->registerLoads();
  }
  
  
  protected function registerLoads()
  {
    $this->loadMigrationsFrom(dirname(dirname(__DIR__)) . '/database/migrations');
  }
}