<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed|min:6|max:12',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('image')){
            $profile = uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/profile/',$profile);
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
}
