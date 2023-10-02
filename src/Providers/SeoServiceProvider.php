<?php

namespace jCube\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
  public function boot(): void
  {
    $this->registerLoads();
    $this->registerComponents();
  }
  
  
  protected function registerLoads()
  {
    $this->loadMigrationsFrom(dirname(dirname(__DIR__)) . '/database/migrations');
  }
  
  protected function registerComponents()
  {
    Blade::anonymousComponentPath(dirname(__DIR__) . '/Views/components');
  }
}