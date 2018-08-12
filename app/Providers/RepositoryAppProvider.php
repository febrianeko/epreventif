<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryAppProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\\Repositories\\Contracts\\IUserRepository', 'App\\Repositories\\Actions\UserRepository');
        $this->app->bind('App\\Repositories\\Contracts\\IRoleRepository', 'App\\Repositories\\Actions\RoleRepository');
        $this->app->bind('App\\Repositories\\Contracts\\IRegionalRepository', 'App\\Repositories\\Actions\RegionalRepository');
        $this->app->bind('App\\Repositories\\Contracts\\IAreaRepository', 'App\\Repositories\\Actions\AreaRepository');
        $this->app->bind('App\\Repositories\\Contracts\\ISiteRepository', 'App\\Repositories\\Actions\SiteRepository');
        $this->app->bind('App\\Repositories\\Contracts\\ITaskRepository', 'App\\Repositories\\Actions\TaskRepository');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
