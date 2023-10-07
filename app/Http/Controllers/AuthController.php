<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function jwt(User $user)
    {
        $payload = [
            'iss' => env('APP_URL'),
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60,
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    public function register()
    {
        $this->validate($this->request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|unique:users|min:11|max:15',
            'password' => 'required',
        ]);
        $input = $this->request->all();
        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->phone_number = $input['phone_number'];
            $user->password = Hash::make($input['password']);
            $user->balance = 0;
            $user->status = 'ACTIVE';
            $user->save();
            $user->roles()->attach(Role::where('slug', 'ROLE_USER')->first());
            // init wallet
            $wallet = new Wallet([
                'beginning_balance' => 0,
                'ending_balance' => 0,
                'debit' => 0,
                'credit' => 0,
            ]);
            $user->wallet()->save($wallet);
            DB::commit();
            return $this->ok('Register success', $user);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->internalServerError($e->getMessage());
        }
    }

    public function authenticate()
    {
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            return $this->badRequest('Email does not exist.');
        }
        if (Hash::check($this->request->input('password'), $user->password)) {
            return $this->ok('Login success', [
                'access_token' => $this->jwt($user),
            ]);
        }
        return $this->badRequest('Email or password is wrong.');
    }
}
