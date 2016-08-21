<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

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
        $fullAccess = implode(',', $this->getTables());
        $data = [
            'access' => $fullAccess,
            'date' => date("Y-m-d H:i:s"),
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

    protected function getTables()
    {
        $result = [];
        foreach (DB::select('SHOW TABLES') as $table) {
            foreach ($table as $key => $name) {
                $result[] = $name;
            }
        }
        return $result;
    }
}
