<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        // Create the trigger

           DB::unprepared('
             CREATE TRIGGER before_update_try BEFORE UPDATE ON users
            FOR EACH ROW
            BEGIN
                IF TIMESTAMPDIFF(MINUTE, OLD.last_failed_attempt, NOW()) >= 2 THEN
                    SET NEW.last_failed_attempt = null;
                    SET NEW.try = 0;
                END IF;
            END
       ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::unprepared('DROP TRIGGER IF EXISTS before_update_try');
    }
};
