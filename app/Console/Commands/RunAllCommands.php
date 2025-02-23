<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunAllCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run multiple Artisan commands in sequence';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('migrate:fresh', ['--seed' => true]);
        $this->call('passport:client', [
            '--personal' => true,
            '--name' => config('auth.access_token_name'),
        ]);
        $this->call('l5-swagger:generate');
        $this->call('optimize');
        $this->call('serve');
    }
}
