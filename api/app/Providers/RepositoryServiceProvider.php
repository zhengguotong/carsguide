<?php

namespace App\Providers;

use App\Repositories\Contracts\IProperty;
use App\Repositories\Contracts\IPropertyAnalytic;

use App\Repositories\Eloquent\PropertyRepository;
use App\Repositories\Eloquent\PropertyAnalyticRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
