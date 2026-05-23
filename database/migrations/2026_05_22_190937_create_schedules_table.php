<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('schedules', function (Blueprint $table) {
    $table->id();

    $table->foreignId('train_id')->constrained()->onDelete('cascade');
    $table->foreignId('route_id')->constrained()->onDelete('cascade');

    $table->dateTime('departure_time');
    $table->dateTime('arrival_time');

    $table->decimal('fare', 8, 2);

    $table->integer('available_seats');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
