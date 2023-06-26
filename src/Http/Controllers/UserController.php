<?php


namespace Transave\ScolaCbt\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaCbt\Actions\User\SearchUsers;
use Transave\ScolaCbt\Actions\User\UpdateUser;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function users()
    {
        return (new SearchUsers(config('scola-cbt.auth_model'), []))->execute();
    }

    public function update(Request $request, $id)
    {
        $input = $request->merge(['user_id' => $id]);
        return (new UpdateUser($input->all()))->execute();
    }

    public function changeEmail()
    {

    }

    public function changePassword()
    {

    }
}