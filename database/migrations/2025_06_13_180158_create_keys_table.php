<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('region_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('period_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('key');
            $table->timestamp('expiration_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keys');
    }
};
