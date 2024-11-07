<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\IncomeExpend;
use Illuminate\Console\Command;

class InstallExpense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:expense';

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
        $this->info('Start Creating Expend for ' . Carbon::now()->format('Y-m-d H:m'));
        foreach ($users as $user) {
            $category = Category::where('type', 'expend')->inRandomOrder()->first();
            $wallet = $user->wallets()->inRandomOrder()->first();
            // dd($wallet->id);
            $amount = rand(1000, 5000);
            IncomeExpend::create([
                'category_id' => $category->id,
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'description' => 'Seeding From Command for ' . Carbon::now()->format('H:m'),
                'amount' => $amount,
                'type' => 'expend',
                'action_date' => Carbon::now()->format('Y-m-d')
            ]);

            $wallet->amount -= $amount;
            $wallet->save();
        }

        $this->info('Finished Creating Expend Data');
    }
}
