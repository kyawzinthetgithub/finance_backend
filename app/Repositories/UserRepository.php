<?php

namespace App\Repositories;

use Illuminate\Http\Request;

class UserRepository
{
    public function store(Request $request)
    {
        dd($request->all());
    }
}
