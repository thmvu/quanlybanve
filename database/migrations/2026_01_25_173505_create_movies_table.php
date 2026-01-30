<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration'); // in minutes
            $table->date('release_date');
            $table->string('poster')->nullable();
            $table->string('trailer_url')->nullable();
            $table->enum('status', ['coming_soon', 'now_showing', 'stop_showing'])->default('coming_soon');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
