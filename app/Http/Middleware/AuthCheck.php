<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        # return $next($request);
        switch (GetRouteName()) {
            case 'admin':
                if (Auth::guard('admin')->check()) {
                    if (!Auth::guard('admin')->user()->is_active) {
                        Auth::guard('admin')->logout();
                        RedirectRoute('admin.login', null, [
                            'flash_error' => config('sites.messages.flash_error.deny')
                        ]);
                    }

                    if (Auth::guard('admin')->user()->auth !== 'D') {
                        config([
                            'auth.isDevel' => false,
                            'auth.isSuper' => Auth::guard('admin')->user()->auth === 'S' ?? false,
                        ]);
                    } else {
                        config([
                            'auth.isDevel' => true,
                            'auth.isSuper' => true,
                        ]);
                    }
                    config([
                        'app.path' => $request->path(),
                        'auth.admin' => Auth::guard('admin')->user()
                    ]);
                    return $next($request);
                }
                session()->put('url.intended', url()->current());
                RedirectRoute('admin.login');
            default:
                if (Auth::guard('web')->check()) {
                    if (!Auth::guard('web')->user()->is_active) {
                        Auth::guard('web')->logout();
                        RedirectRoute('login', null, [
                            'flash_error' => config('sites.messages.flash_error.deny')
                        ]);
                    }
                    config([
                        'auth.user' => Auth::guard('user')->user(),
                        'app.path' => $request->path()
                    ]);
                    return $next($request);
                }
                RedirectRoute('login');
        }
    }
}
