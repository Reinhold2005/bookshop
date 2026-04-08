<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->decimal('average_rating', 2, 1)->default(0);
            $table->integer('total_ratings')->default(0);
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'total_ratings']);
        });
    }
};