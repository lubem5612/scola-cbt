<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\Auth\ForgotPassword;
use Transave\ScolaCbt\Actions\Auth\ResetPassword;
use Transave\ScolaCbt\Helpers\ResponseHelper;

class PasswordController extends Controller
{
    /**
     * PasswordController constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Forgot password request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function forgotPassword(Request $request)
    {
        return (new ForgotPassword($request->all()))->execute();
    }

    /**
     * Reset user password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function resetPassword(Request $request)
    {
        return (new ResetPassword($request->all()))->execute();
    }
}