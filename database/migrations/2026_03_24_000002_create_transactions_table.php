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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->decimal('amount', 12, 2);
            $table->string('type'); // deposit | withdraw | transfer

            $table->uuid('account_id');
            $table->foreign('account_id')->references('id')->on('accounts')->cascadeOnDelete();

            $table->uuid('transfer_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
