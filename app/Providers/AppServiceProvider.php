<?php

namespace App\Providers;

use App\Repositories\AssuntoListInterface;
use App\Repositories\AssuntoRepositoryInterface;
use App\Repositories\AutorListInterface;
use App\Repositories\AutorRepositoryInterface;
use App\Repositories\Eloquent\EloquentAssuntoRepository;
use App\Repositories\Eloquent\EloquentAutorRepository;
use App\Repositories\Eloquent\EloquentLivroRepository;
use App\Repositories\Eloquent\EloquentRelatorioRepository;
use App\Repositories\LivroRepositoryInterface;
use App\Repositories\RelatorioRepositoryInterface;
use App\Services\AssuntoService;
use App\Services\AssuntoServiceInterface;
use App\Services\AutorService;
use App\Services\AutorServiceInterface;
use App\Services\LivroService;
use App\Services\LivroServiceInterface;
use App\Services\RelatorioService;
use App\Services\RelatorioServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AutorRepositoryInterface::class, EloquentAutorRepository::class);
        $this->app->bind(AssuntoRepositoryInterface::class, EloquentAssuntoRepository::class);
        $this->app->bind(LivroRepositoryInterface::class, EloquentLivroRepository::class);
        $this->app->bind(AutorListInterface::class, EloquentAutorRepository::class);
        $this->app->bind(AssuntoListInterface::class, EloquentAssuntoRepository::class);
        $this->app->bind(RelatorioRepositoryInterface::class, EloquentRelatorioRepository::class);

        $this->app->bind(AutorServiceInterface::class, AutorService::class);
        $this->app->bind(AssuntoServiceInterface::class, AssuntoService::class);
        $this->app->bind(LivroServiceInterface::class, LivroService::class);
        $this->app->bind(RelatorioServiceInterface::class, RelatorioService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
