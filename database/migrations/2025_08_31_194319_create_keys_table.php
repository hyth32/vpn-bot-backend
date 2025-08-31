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
            $table->integer('order_id');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();
            $table->string('config_id');
            $table->string('config_name');
            $table->timestamp('expiration_date');

            $table->softDeletes();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keys');
    }
};
