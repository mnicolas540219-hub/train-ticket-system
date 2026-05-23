<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            if (! Schema::hasColumn('routes', 'origin')) {
                $table->string('origin')->after('id');
            }

            if (! Schema::hasColumn('routes', 'destination')) {
                $table->string('destination')->after('origin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            if (Schema::hasColumn('routes', 'destination')) {
                $table->dropColumn('destination');
            }

            if (Schema::hasColumn('routes', 'origin')) {
                $table->dropColumn('origin');
            }
        });
    }
};
