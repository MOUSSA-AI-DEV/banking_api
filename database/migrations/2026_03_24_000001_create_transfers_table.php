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
        Schema::create('transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->decimal('amount', 12, 2);
            $table->string('status');

            $table->uuid('source_account_id');
            $table->uuid('destination_account_id');

            $table->foreign('source_account_id')->references('id')->on('accounts')->cascadeOnDelete();
            $table->foreign('destination_account_id')->references('id')->on('accounts')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
