<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SaveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-users';

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

        $users = User::all();

        foreach ($users as $user) {

            $user->update([
                'name' => rand(1, 145) . $user->name,

            ]);

        }
    }
}
