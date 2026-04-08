<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('cancellation_reason')->nullable();
            $table->timestamp('cancellation_requested_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('refund_status')->default('none'); // none, pending, processing, completed, failed
            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->timestamp('refund_processed_at')->nullable();
            $table->text('refund_notes')->nullable();
            $table->boolean('cancellation_eligible')->default(true);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_reason', 
                'cancellation_requested_at', 
                'cancelled_at',
                'refund_status',
                'refund_amount',
                'refund_processed_at',
                'refund_notes',
                'cancellation_eligible'
            ]);
        });
    }
};