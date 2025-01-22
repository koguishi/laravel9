<?php

namespace App\Providers;

use app\events\VideoEvent;
use app\repository\DBTransaction;
use app\repository\eloquent\AtletaRepository;
use app\repository\eloquent\CategoriaRepository;
use app\repository\eloquent\VideoRepository;
use app\services\FileStorage;
use core\domain\event\VideoCreatedEvent;
use core\domain\repository\AtletaRepositoryInterface;
use core\domain\repository\CategoriaRepositoryInterface;
use core\domain\repository\VideoRepositoryInterface;
use core\usecase\interfaces\FileStorageInterface;
use core\usecase\interfaces\TransactionInterface;
use core\usecase\video\VideoEventManagerInterface;
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

        $this->app->bind(
            FileStorageInterface::class,
            FileStorage::class
        );

        $this->app->bind(
            VideoEventManagerInterface::class,
            VideoEvent::class
        );

        /**
         * DB Transaction
         */
        $this->app->bind(
            TransactionInterface::class,
            DBTransaction::class,
        );        
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

        $this->app->singleton(
            VideoRepositoryInterface::class,
            VideoRepository::class
        );
    }
}
