<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->string('amount');
            $table->string('currency')->default('RUB');
            $table->boolean('test')->default(true);
            $table->boolean('paid')->default(false);
            $table->json('metadata')->nullable();
            $table->integer('region_id')->nullable();
            $table->foreign('region_id')
                ->references('id')
                ->on('regions')
                ->nullOnDelete();
            $table->integer('period_id')->nullable();
            $table->foreign('period_id')
                ->references('id')
                ->on('periods')
                ->nullOnDelete();
            $table->integer('key_count');
            $table->boolean('free')->default(false);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
