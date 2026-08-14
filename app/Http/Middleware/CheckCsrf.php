<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Auth;

class CheckCsrf extends ValidateCsrfToken
{
    protected $except = [];

    public function handle($request, Closure $next): Response
    {
        if ($request->route()->named('admin.logout')) {
            if (!Auth::check() || Auth::guard()->viaRemember()) {
                $this->except[] = app('backend.prefix') . '/logout';
            }
        }

        return parent::handle($request, $next);
    }
}
