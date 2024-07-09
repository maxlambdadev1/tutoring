<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Customized code
        Gate::define('action-owner', function (User $user) {
            return $user->user_role->name === 'admin' && $user->admin->admin_role->name === 'owner';
        });
        Gate::define('action-manager', function (User $user) {
            $user_type = $user->admin->admin_role->name ?? '';
            return $user->user_role->name === 'admin' && ($user_type === 'owner' || $user_type == 'manager');
        });
    }
}
