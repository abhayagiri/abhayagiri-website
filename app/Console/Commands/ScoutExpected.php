<?php

namespace App\Console\Commands;

use App\Search\Pages;
use Illuminate\Console\Command;

class ScoutExpected extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:expected';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the expected number of index records to import';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $class = Pages::class;
        $total = 0;
        $this->info('Expected number of index records to import per model:');
        $this->info('');
        foreach ($class::getExpectedImportCount() as $model => $count) {
            $this->info('    ' . $model . ': ' . $count);
            $total += $count;
        }
        $this->info('');
        $this->info('Expected number of index records to import for ' .
                    $class . ': ' . $total);
    }
}
