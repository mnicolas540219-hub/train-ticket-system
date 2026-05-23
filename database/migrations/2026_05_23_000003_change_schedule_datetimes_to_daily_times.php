<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Some database drivers (sqlite in-memory used by tests) do not support ALTER ... MODIFY.
        // Skip these raw statements during tests or when using sqlite.
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE schedules MODIFY departure_time TIME NOT NULL');
            DB::statement('ALTER TABLE schedules MODIFY arrival_time TIME NOT NULL');
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE schedules MODIFY departure_time DATETIME NOT NULL');
            DB::statement('ALTER TABLE schedules MODIFY arrival_time DATETIME NOT NULL');
        }
    }
};
