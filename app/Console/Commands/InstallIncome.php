<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\IncomeExpend;
use Illuminate\Console\Command;

class InstallIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:income';

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
        $users = User::whereHas('wallets')->get();
        $this->info('Start Creating Income for ' . Carbon::now()->format('Y-m-d H:m'));
        foreach ($users as $user) {
            $wallets = $user->wallets()->get();
            foreach ($wallets as $wallet) {
                $amount = rand(10000, 50000);
                $category = Category::where('type', 'income')->inRandomOrder()->first();
                IncomeExpend::create([
                    'category_id' => $category->id,
                    'wallet_id' => $wallet->id,
                    'user_id' => $user->id,
                    'description' => 'Seeding From Command for ' . Carbon::now()->format('H:m'),
                    'amount' => $amount,
                    'type' => 'income',
                    'action_date' => Carbon::now()->format('Y-m-d')
                ]);

                $wallet->amount += $amount;
                $wallet->save();
            }
        }

        $this->info('Finished Creating Income Data');
    }
}
