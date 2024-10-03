<?php

namespace App\Providers;

use app\repository\eloquent\AtletaRepository;
use app\repository\eloquent\CategoriaRepository;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\CategoriaRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindRepositories();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function bindRepositories()
    {
        /**
         * Repositories
         */
        $this->app->singleton(
            CategoriaRepositoryInterface::class,
            CategoriaRepository::class
        );

        $this->app->singleton(
            AtletaRepositoryInterface::class,
            AtletaRepository::class
        );

    }    
}
