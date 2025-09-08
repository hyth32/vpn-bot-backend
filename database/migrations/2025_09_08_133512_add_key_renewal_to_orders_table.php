<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('renew')->default(false);
            $table->integer('renewed_key_id')->nullable();
            $table->foreign('renewed_key_id', 'fk-order-renewed-key-1')
                ->references('id')
                ->on('keys');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('fk-order-renewed-key-1');
            $table->dropColumn('renewed_key_id');
        });
    }
};
