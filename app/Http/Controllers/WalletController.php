<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->can('SHOW_USER')) {
            $wallet = Wallet::where('user_id', $user->id)->first();
            return $this->ok('Get Wallet success', $wallet);
        } else {
            return $this->unauthorized();
        }
    }
}
