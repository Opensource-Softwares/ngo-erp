<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS finance');
        DB::statement('CREATE SCHEMA IF NOT EXISTS hr');
        DB::statement('CREATE SCHEMA IF NOT EXISTS procurement');
        DB::statement('CREATE SCHEMA IF NOT EXISTS grants');
        DB::statement('CREATE SCHEMA IF NOT EXISTS audit');
        DB::statement('CREATE SCHEMA IF NOT EXISTS config');
    }

    public function down(): void
    {
        // CASCADE drops all tables inside — careful in production
        DB::statement('DROP SCHEMA IF EXISTS finance CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS hr CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS procurement CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS grants CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS audit CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS config CASCADE');
    }
};