<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Session\Session;


class LockoutLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:lockout-login';

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

        $users = User::all();//User::where('try', '>=', 0)->get();


        foreach ($users as $user) {


            if ($user->last_failed_attempt && $user->last_failed_attempt->diffInMinutes(now()) >= 2) {


                $user->update([
                    'try' => 0,
                    'last_failed_attempt' => null

                ]);

            }
            Log::info("last_failed_attempt restarted");
        }

    }
}
