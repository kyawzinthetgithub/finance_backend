<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\WalletType;
use App\Models\IncomeExpend;
use Illuminate\Console\Command;
use Illuminate\Support\Testing\Fakes\Fake;

class InstallAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:account';

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
        $walletData = [
            [
                'name' => 'AYA Bank',
                'amount' => rand(1000, 10000),
                'wallet_type_id' => 2
            ],
            [
                'name' => 'Hand In Cash',
                'amount' => rand(1000, 10000),
                'wallet_type_id' => 1
            ],
            [
                'name' => 'Mobile Banking',
                'amount' => rand(1000, 10000),
                'wallet_type_id' => 3
            ]
        ];
        Wallet::truncate();
        IncomeExpend::truncate();
        $this->info('Creating User Account');
        $users = User::all();
        $category = Category::where('name', 'Deposite')->first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Deposite',
                'type' => 'income'
            ]);
        };
        foreach($users as $user) {
            foreach($walletData as $wallet){
                $data = Wallet::create([
                    'user_id' => $user->id,
                    'wallet_type_id' => $wallet['wallet_type_id'],
                    'name' => $wallet['name'],
                    'amount' => $wallet['amount'],
                ]);

                IncomeExpend::create([
                    'category_id' => $category->id,
                    'wallet_id' => $data->id,
                    'user_id' => $user->id,
                    'description' => 'Wallet Creation income',
                    'amount' => $wallet['amount'],
                    'type' => 'income',
                    'action_date' => Carbon::now(),
                ]);
            }
        }

        $this->info('User Account Created Successfully');
        
    }
}
