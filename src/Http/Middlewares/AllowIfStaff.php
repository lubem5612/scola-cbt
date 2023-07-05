<?php

namespace Transave\ScolaCbt\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Transave\ScolaCbt\Helpers\ResponseHelper;

class AllowIfStaff
{
    use ResponseHelper;

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!empty($user) && in_array($user->role, ['staff', 'examiner', 'manager', 'admin'])) {
            return $next($request);
        }
        return $this->sendError('You are not an Staff.', [], 401);
    }
}
