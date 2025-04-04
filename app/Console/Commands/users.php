<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class users extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = config('users');
        User::truncate();
        foreach($users as $user) {
        User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => Hash::make($user['password'])
        ]);
       }
    }
}
