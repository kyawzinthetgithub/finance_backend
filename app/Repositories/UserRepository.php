<?php

namespace App\Repositories;

use App\Models\User;
use Hashids\Hashids;
use App\Models\Image;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;

class UserRepository
{
    protected $hashids;
    protected $cloudinary;

    public function __construct(Hashids $hashids, CloudinaryService $cloudinaryService)
    {
        $this->hashids = $hashids;
        $this->cloudinary = $cloudinaryService;
    }

    public function byHash($id)
    {
        return $this->hashids->decode($id)[0];
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6|max:12',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = $this->cloudinary->upload($request->file('image'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'currency' => $request->currency,
            'password' => Hash::make($request->password),
            'image' => $image && $request->image ? $image->id : null
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
                'user' => new UserResource($user),
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
        $userImage = Image::where('id', $user->image)->latest()->first();
        $request->validate([
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            if ($userImage) {
                $image = $this->cloudinary->update($userImage, $request->file('image'));
            } else {
                $image = $this->cloudinary->upload($request->file('image'));
            }
        }
        $user->name = $request->name;
        $user->email = $request->email ?? $user->email;
        $user->currency = $request->currency ?? $user->email;
        $user->image = $image && $request->file('image') ? $image->id : $user->image;
        $user->save();

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
