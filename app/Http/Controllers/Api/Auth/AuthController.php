<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
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
        $user = User::where('email', $request->email)->latest()->first();
        abort_if($user, 442, 'You already registered');
        $response = $this->repo->store($request);
        return $response;
    }

    public function login(Request $request)
    {
        $response = $this->repo->login($request);
        return $response;
    }

    public function logout(Request $request) {
        $response = $this->repo->logout($request);
        return $response;
    }

    public function edit($id)
    {
        $response = $this->repo->edit($id);
        return (new UserResource($response));
    }

    public function update(Request $request,$id)
    {
        $response = $this->repo->update($request,$id);
        return (new UserResource($response));
    }

    public function changePassword(Request $request,$id)
    {
        $response = $this->repo->changePassword($request,$id);
        return (new UserResource($response));
    }
}
