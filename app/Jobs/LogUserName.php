<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogUserName implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // public $username
        // $this->username = $username;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
    $users = User::where('email', 'LIKE', '%example%')->get();


        foreach ($users as $user) {

            $user->delete();
        }

      log::info('User with email containing "example" has been deleted');

    }
}
