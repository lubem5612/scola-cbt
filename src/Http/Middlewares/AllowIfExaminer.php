<?php

namespace Transave\ScolaCbt\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Transave\ScolaCbt\Helpers\ResponseHelper;

class AllowIfExaminer
{
    use ResponseHelper;

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!empty($user) && in_array($user->role, ['examiner', 'admin'])) {
            return $next($request);
        }
        return $this->sendError('You are not an Examiner.', [], 401);
    }
}
