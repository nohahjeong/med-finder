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
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('active_ingredient');
            $table->string('manufacturer');
            $table->text('presentation');
            $table->decimal('price_max', 12, 2)->nullable(); // CMED PMC 18%
            $table->string('registration', 20)->nullable()->unique();
            $table->string('slug')->unique();
            $table->timestamps();

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
