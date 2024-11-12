<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Budget;
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
    protected $signature = 'install:expense {date}'; // php artisan install:expense 2024-11-26 

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
        $inputDate = $this->argument('date');
        $expenseCreationDate = Carbon::now()->format('Y-m-d');
        if ($inputDate) {
            $expenseCreationDate = Carbon::parse($inputDate)->format('Y-m-d');
        }
        $users = User::whereHas('wallets')->get();
        $this->info('Start Creating Expend for ' . Carbon::now()->format('Y-m-d H:m'));
        foreach ($users as $user) {
            $wallets = $user->wallets()->get();
            foreach ($wallets as $wallet) {
                $amount = rand(1000, 5000);
                $category = Category::where('type', 'expend')->inRandomOrder()->first();
                IncomeExpend::create([
                    'category_id' => $category->id,
                    'wallet_id' => $wallet->id,
                    'user_id' => $user->id,
                    'description' => 'Seeding From Command for ' . Carbon::now()->format('H:m'),
                    'amount' => $amount,
                    'type' => 'expend',
                    'action_date' => $expenseCreationDate,
                ]);

                $wallet->amount -= $amount;
                $wallet->save();
                $budget = Budget::where('category_id', $category->id)->where('expired_at', '>', Carbon::now())->where('user_id', $user->id)->latest()->first();
                if ($budget) {
                    $budget->update([
                        'spend_amount' => $budget->amount + $amount,
                        'usage' => $budget->usage + $amount,
                        'remaining_amount' => $budget->remaining - $amount
                    ]);
                    $budget->refresh();
                }
            }
        }

        $this->info('Finished Creating Expend Data');
    }
}
