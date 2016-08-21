<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

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
     * @return mixed
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
            $existing->update($data);
        } else {
            DB::table('mahaguild')->insert(array_merge([
                'avatar' => 'logothree.jpg',
                'title' => 'Administrator',
            ], $data));
        }
        return true;
    }

    private function getFullAccessString()
    {
        $pages = array_map(function ($page) {
            return $page->url_title;
        }, Mahapanel::mahapanelPages()->get());
        return implode(',', $pages);
    }
}
