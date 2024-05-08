<?php

namespace App\Providers;

use App\View\Components\Admin\Layouts\AppLayout as AdminAppLayout;
use App\View\Components\Tutor\Layouts\AppLayout as TutorAppLayout;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

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

        Paginator::useBootstrapFive();

        Mail::extend('sendgrid', function () {
            return (new SendgridTransportFactory)->create(
                new Dsn(
                    'sendgrid+api',
                    'default',
                    config('services.sendgrid.key')
                )
            );
        });
    }
}
