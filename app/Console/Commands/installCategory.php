<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class installCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:category';

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
        $categories = config('category');
        Category::truncate();
        $this->info("Category start creating...");
        $total = count($categories);
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        foreach ($categories as $category) {
            Category::create([
                'name' => $category["name"],
                'type' => $category["type"]
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->info("\nCategory create success...");
    }
}
