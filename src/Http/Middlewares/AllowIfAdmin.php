<?php


namespace Transave\ScolaCbt\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Transave\ScolaCbt\Helpers\ResponseHelper;

class AllowIfAdmin
{
    use ResponseHelper;
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!empty($user) && $user->role == 'admin') {
            return $next($request);
        }
        return $this->sendError('you must log in as admin to perform this operation', [], 401);
    }
}