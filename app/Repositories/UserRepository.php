<?php

namespace App\Repositories;

use App\Models\User;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $hashids;
    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    public function byHash($id)
    {
        return $this->hashids->decode($id)[0];
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6|max:12',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $profile = uniqid() . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/profile/', $profile);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $profile
        ]);

        $token = $user->createToken($request->name);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function login($request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->latest()->first();
        abort_if(!$user, 422, 'You haven\'t register yet');
        if ($user && !Hash::check($request->password, $user->password)) {
            return [
                'message' => 'Unauthenticated'
            ];
        } else {
            $token = $user->createToken($user->name);
            return [
                'user' => $user,
                'token' => $token->plainTextToken
            ];
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'message' => 'Logout Success'
        ];
    }

    public function edit($id)
    {
        $user = User::findOrFail($this->byHash($id));
        return $user;
    }

    public function update($request, $id)
    {
        $user = User::findOrFail($this->byHash($id));
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $userImg = $user->image;
            if ($userImg) {
                $filePath = storage_path('app/public/profile/' . $userImg);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                } else {
                    return ['message' => 'File Not Found'];
                }
            }
            $profile = uniqid() . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/profile/', $profile);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $profile
        ]);

        return $user;
    }

    public function changePassword($request, $id)
    {
        $user = User::findOrFail($this->byHash($id));
        $request->validate([
            'old_password' => 'required|min:6|max:12',
            'new_password' => 'required|min:6|max:12',
            'confirm_password' => 'required|min:6|max:12|same:new_password'
        ]);
        abort_if(!Hash::check($request->old_password, $user->password), 401, 'wrong password');
        $user->update([
            'password' => Hash::make($request->confirm_password)
        ]);
        return $user;
    }
}
