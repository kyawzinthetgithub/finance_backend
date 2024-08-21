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

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }
    //api user register
    public function register(Request $request)
    {
        $user = User::where('email',$request->email)->latest()->first();
        abort_if($user,442,$message='You already registered');
        $response = $this->repo->store($request);
        return $response;
    }
}
