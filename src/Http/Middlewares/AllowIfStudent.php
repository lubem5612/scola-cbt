<?php

namespace Transave\ScolaCbt\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Transave\ScolaCbt\Helpers\ResponseHelper;

class AllowIfStudent
{
    use ResponseHelper;

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!empty($user) && in_array($user->role, ['staff', 'examiner', 'manager', 'admin', 'student'])) {
            return $next($request);
        }
        return $this->sendError('You are not a student.', [], 401);
    }
}