<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index(Request $request)
    {
        return $this->ok('Get about success', [
            'app_name' => env('APP_NAME'),
            'version' => '0.1.1-SNAPSHOT',
            'author' => 'Jin Huy',
        ]);
    }
}
