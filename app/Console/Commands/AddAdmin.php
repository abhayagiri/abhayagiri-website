<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Illuminate\Console\Command\InputOption;

use App\Util;
use App\Legacy\Mahapanel;

class AddAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Mahaguild administrator';

    /**
     * Tables to ignore when building mahaguild permissions.
     *
     * @var array
     */
    protected $ignoreTablesForMahaguild = [
        'columns',
        'migrations',
        'options',
        'rideshare',
    ];

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
        $email = $this->argument('email');
        $data = [
            'access' => $this->getFullAccessString(),
            'date' => Carbon::now(),
            'email' => $email,
            'user' => 0,
        ];
        $existing = DB::table('mahaguild')->where('email', '=', $email);
        if ($existing->count() > 0) {
            $this->info('Updating admin ' . $email . '.');
            $existing->update($data);
        } else {
            $this->info('Adding admin ' . $email . '.');
            DB::table('mahaguild')->insert(array_merge([
                'avatar' => 'logothree.jpg',
                'title' => 'Administrator',
            ], $data));
        }
    }

    private function getFullAccessString()
    {
        $pages = Mahapanel::mahapanelPages()->pluck('url_title')->toArray();
        return implode(',', $pages);
    }
}
