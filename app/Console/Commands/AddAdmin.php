<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;

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
    protected $description = 'Add administrator';

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
        if ($user = User::where('email', $email)->first()) {
            $this->info('Updating admin ' . $email . '.');
            $user->is_super_admin = true;
            $user->save();
        } else {
            $this->info('Adding admin ' . $email . '.');
            User::create([
                'name' => 'Administrator',
                'email' => $email,
                'password' => md5(openssl_random_pseudo_bytes(100)),
                'is_super_admin' => true,
            ]);
        }
    }
}
