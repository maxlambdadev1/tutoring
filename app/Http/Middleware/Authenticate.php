<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $routeName = 'tutor.login';
        $domain = $request->getHost();
        if (env('ADMIN') == $domain) $routeName = 'admin.login';
        if (env('RECRUITER') == $domain) $routeName = 'recruiter.login';
        if (env('PARENT') == $domain) $routeName = 'parent.login';

        return $request->expectsJson() ? null : route($routeName);
    }
}
