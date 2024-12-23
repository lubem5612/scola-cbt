<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\Auth\ChangeEmail;
use Transave\ScolaCbt\Actions\Auth\ChangePassword;
use Transave\ScolaCbt\Actions\User\DeleteUser;
use Transave\ScolaCbt\Actions\User\SearchUsers;
use Transave\ScolaCbt\Actions\User\UpdateUser;
use Transave\ScolaCbt\Http\Models\User;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    /**
     * Get listing for users except the admin
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function users()
    {
        return (new SearchUsers(User::class, []))->execute();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function user($id)
    {
        return (new SearchUsers(User::class, [], $id))->execute();
    }

    /**
     * Update a specified user account
     *
     * @param Request $request)
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->merge(['user_id' => $id]);
        return (new UpdateUser($input->all()))->execute();
    }

    /**
     * Change email of authenticated user
     *
     * @param Request $request
     * @return ChangeEmail
     */
    public function changeEmail(Request $request)
    {
        $input = $request->merge(['user_id' => auth()->id()]);
        return (new ChangeEmail($input->all()))->execute();
    }

    /**
     * Change password of authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function changePassword(Request $request)
    {
        $input = $request->merge(['user_id' => auth()->id()]);
        return (new ChangePassword($input->all()))->execute();
    }


    /**
     * Delete a specified account
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteUser(['user_id' => $id]))->execute();
    }
}