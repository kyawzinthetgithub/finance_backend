<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\WalletType;
use Illuminate\Console\Command;
use Illuminate\Support\Testing\Fakes\Fake;

class InstallAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install-account';

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
        $wallet = WalletType::all()->pluck('id');
        $accountData = [
            [
                'name' =>  fake()->randomElement(['Yoma Bank', 'KBZ Bank', 'AYA Bank', 'MAB Bank']),
                'amount' => rand(1000, 10000),
                'wallet_type_id' => fake()->randomElement($wallet)
            ],
        ];
        $users = User::all();
        
        
    }
}
