<?php


namespace Transave\ScolaCbt\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Transave\ScolaCbt\Helpers\ResponseHelper;

class VerifiedAccount
{
    use ResponseHelper;
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!empty($user) && $user->is_verified) {
            return $next($request);
        }
        return $this->sendError('you must verify your account to perform this operation', [], 401);
    }
}