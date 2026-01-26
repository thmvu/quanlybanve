<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('row'); // A, B, C
            $table->integer('number'); // 1, 2, 3
            $table->enum('type', ['normal', 'vip', 'couple'])->default('normal');
            $table->timestamps();

            // Unique constraint on room, row, number
            $table->unique(['room_id', 'row', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
