<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->unique(); // easypaisa, jazzcash, bank_transfer
            $table->boolean('is_enabled')->default(false);
            $table->string('account_name', 150)->nullable();
            $table->string('account_number', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('bank_name', 150)->nullable();
            $table->string('iban', 100)->nullable();
            $table->string('branch', 150)->nullable();
            $table->string('qr_image_path')->nullable();
            $table->text('instructions')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
