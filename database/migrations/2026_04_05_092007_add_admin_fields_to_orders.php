<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->date('actual_delivery_date')->nullable();
            $table->string('tracking_number')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('status')->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['actual_delivery_date', 'tracking_number', 'admin_notes']);
        });
    }
};