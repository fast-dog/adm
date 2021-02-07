<?php

namespace FastDog\Adm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class Admin
 * @package FastDog\Adm\Http\Middleware
 * @author Андрей Мартынов <d.g.dev482@gmail.com>
 */
class Admin
{

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        auth()->check();

        if (auth()->guest()) {
            return ($request->ajax()) ? response()->json(['error' => 'Unauthorized.'], 401) :
                redirect()->guest(config('core.admin_path', 'admin').'/login');
        }

        return $next($request);
    }
}
