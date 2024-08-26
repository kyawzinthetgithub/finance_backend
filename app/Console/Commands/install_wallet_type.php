<?php

namespace App\Console\Commands;

use App\Models\WalletType;
use Illuminate\Console\Command;

class install_wallet_type extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:wallet_type';

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
        $types = config('wallet_types');
        WalletType::truncate();
        if ($types) {
            $this->info('wallet type start create');
            foreach ($types as $type) {
                WalletType::create([
                    'name' => $type
                ]);
            }
            $this->info('wallet type start create successfully');
        }
    }
}
