<?php

namespace App\Providers;

use App\View\Components\Admin\Layouts\AppLayout as AdminAppLayout;
use App\View\Components\Tutor\Layouts\AppLayout as TutorAppLayout;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Blade::component('admin-app-layout', AdminAppLayout::class);
        Blade::component('tutor-app-layout', TutorAppLayout::class);
    }
}
