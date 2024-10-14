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
        User::create(
            [
            'name' => 'test',
            'email' => 'test@email.com',
            'password' => Hash::make('secret')
            ],
            [
            'name' => 'alex',
            'email' => 'alex@gmail.com',
            'password' => Hash::make('secret')
            ],
            [
                'name' => 'Bruno',
                'email' => 'bruno@gmail.com',
                'password' => Hash::make('secret')
            ]
         );
    }
}
