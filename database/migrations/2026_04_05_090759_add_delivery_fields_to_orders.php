<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->string('delivery_method')->default('standard');
            $table->text('delivery_address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('estimated_delivery')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'delivery_fee', 'delivery_method', 'delivery_address', 'city', 'postal_code', 'phone', 'estimated_delivery']);
        });
    }
};