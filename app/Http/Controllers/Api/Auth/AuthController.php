<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $repo;

    public function __construct(UserRepository $repo){

    }
    //api user register
    public function register(Request $request)
    {




        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|unique:users',
        //     'password' => 'required|confirmed|min:6|max:12'
        // ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password)
        // ]);

        // $token = $user->createToken($request->name);

        // return [
        //     'user' => $user,
        //     'token' => $token->plainTextToken
        // ];
    }
}
