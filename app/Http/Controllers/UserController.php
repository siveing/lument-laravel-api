<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $request;
    private $user;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = $request->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($this->user->can('SHOW_USER')) {
            $users = User::paginate(20);
            return $this->ok('Get users success', $users);
        } else {
            return $this->unauthorized();
        }
    }

    public function profile(Request $request)
    {
        if ($this->user->can('SHOW_USER_PROFILE')) {
            return $this->ok('Get profile success', $this->user);
        } else {
            return $this->unauthorized();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($this->user->can('UPDATE_USER')) {
            $input = $this->request->all();
            $user = User::find('id');
            if (!$user) {
                return $this->badRequest('User id ' . $id . ' not found');
            }
            $user->fill($input);
            $user->save();
            return $this->ok('Update user success', $user);
        } else {
            return $this->unauthorized();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->user->can('DESTROY_USER')) {
            $user = User::find('id');
            if (!$user) {
                return $this->badRequest('User id ' . $id . ' not found');
            }

            $user->delete();
            return $this->ok('Delete user success', $user);
        } else {
            return $this->unauthorized();
        }
    }
}
