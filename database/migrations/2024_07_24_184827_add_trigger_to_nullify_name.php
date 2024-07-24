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
          // Create the trigger
          DB::unprepared('
          CREATE TRIGGER before_update_nullify_name BEFORE UPDATE ON users
          FOR EACH ROW
          BEGIN
              SET NEW.name = NULL;
          END
      ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::unprepared('DROP TRIGGER IF EXISTS before_update_nullify_name');
    }
};
