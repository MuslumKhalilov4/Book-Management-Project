<?php

namespace App\Providers;

use App\Repositories\Implementations\CategoryRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Implementations\CategoryService;
use App\Services\Interfaces\CategoryServiceInterface;
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
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->setLocale(session('locale', config('app.locale')));
    }
}
