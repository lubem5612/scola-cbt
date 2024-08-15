<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\Auth\ChangeEmail;
use Transave\ScolaCbt\Actions\Auth\ChangePassword;
use Transave\ScolaCbt\Actions\Auth\ForgotPassword;
use Transave\ScolaCbt\Actions\Auth\Login;
use Transave\ScolaCbt\Actions\Auth\Register;
use Transave\ScolaCbt\Actions\Auth\ResendEmailVerification;
use Transave\ScolaCbt\Actions\Auth\ResetPassword;
use Transave\ScolaCbt\Actions\Auth\VerifyEmail;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Http\Models\User;

class AuthController extends Controller
{
    use ResponseHelper;
    private  User $user;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['login', 'register']);
    }

    /**
     * Login user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);
        return (new Login($data))->execute();
    }

    /**
     * Register a new account
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function register(Request $request)
    {
        return (new Register($request->all()))->execute();
    }

    /**
     * Get authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function user(Request $request)
    {
        try {
            return $this->sendSuccess($request->user(), 'authenticated user retrieved successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    /**
     * Log out user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return $this->sendSuccess(null, 'user logged out successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    /**
     * Verify created account
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function verifyEmail(Request $request)
    {
        return (new VerifyEmail($request->all()))->execute();
    }

    /**
     * Resend account verification token for auth user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function resendToken(Request $request)
    {
        return (new ResendEmailVerification($request->all()))->execute();
    }

}