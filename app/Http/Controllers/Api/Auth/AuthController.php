<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\User\UserResource;
use GuzzleHttp\Exception\ClientException;

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
        $res = $this->repo->store($request);
        $response = [
            'user' => new UserResource($res['user']),
            'token' => $res['token']
        ];
        return $response;
    }

    public function login(Request $request)
    {
        $res = $this->repo->login($request);
        return $res;
    }

    public function logout(Request $request)
    {
        $response = $this->repo->logout($request);
        return $response;
    }

    public function edit($id)
    {
        $response = $this->repo->edit($id);
        return (new UserResource($response));
    }

    public function update(Request $request, $id)
    {
        $response = $this->repo->update($request, $id);
        return (new UserResource($response));
    }

    public function changePassword(Request $request, $id)
    {
        $response = $this->repo->changePassword($request, $id);
        $result = new UserResource($response);
        $message = "Your Password Updated Successfully";
        return json_response(200, $message, $result);
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'github', 'google'])) {
            return response()->json(['error' => 'Please login using facebook, github or google'], 422);
        }
    }

    public function socialLoginUser(Request $request, $provider)
    {
        logger( $request->access_token);

        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        try {
            $user = Socialite::driver($provider)->userFromToken( $request->access_token);
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName() ? $user->getName() : $user->getNickname(),
                'currency' => 'ks' //default
            ]
        );
        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId(),
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );
        $token = $userCreated->createToken($provider)->plainTextToken;
        logger($token);
        return [
            'user' => $userCreated,
            'token' => $token
        ];
    }
}
