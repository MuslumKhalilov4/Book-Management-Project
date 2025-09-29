<?php

namespace App\Providers;

use App\Services\Implementations\AuthorService;
use App\Services\Implementations\CategoryService;
use App\Services\Implementations\SortOrderService;
use App\Services\Interfaces\AuthorServiceInterface;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Services\Interfaces\SortOrderServiceInterface;
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
        $this->app->bind(AuthorServiceInterface::class, AuthorService::class);
        $this->app->bind(SortOrderServiceInterface::class, SortOrderService::class);
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
