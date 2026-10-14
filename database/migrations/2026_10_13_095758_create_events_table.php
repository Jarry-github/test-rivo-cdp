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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->string('event_name', 100);
            $table->json('properties')->nullable();
            $table->dateTime('occurred_at');

            $table->timestamps();

            $table->index(
                ['customer_id', 'occurred_at'],
                'events_customer_occurred_idx'
            );

            $table->index(
                ['event_name', 'occurred_at', 'customer_id'],
                'events_name_occurred_customer_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
