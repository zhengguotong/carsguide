<?php

namespace App\Providers;

use App\Repositories\Contracts\IItem;
use App\Repositories\Eloquent\ItemRepository;
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
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IItem::class, ItemRepository::class);
    }
}
