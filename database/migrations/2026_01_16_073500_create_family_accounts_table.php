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
        Schema::create('family_accounts', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_balance', 15, 2)->default(0);
            $table->decimal('interest_rate', 5, 2)->default(4.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_accounts');
    }
};
