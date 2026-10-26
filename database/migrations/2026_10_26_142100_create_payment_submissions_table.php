<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('membership_plan_id')->constrained('membership_plans')->cascadeOnDelete();
            $table->foreignId('renewal_order_id')->nullable()->constrained('renewal_orders')->nullOnDelete();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->foreignId('membership_id')->nullable()->constrained('memberships')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('transaction_reference', 100);
            $table->dateTime('paid_at');
            $table->string('screenshot_path');
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('receipt_number', 50)->nullable();
            $table->timestamps();

            $table->unique(['payment_method_id', 'transaction_reference'], 'pay_sub_method_trx_unique');
            $table->index(['member_id', 'status'], 'pay_sub_member_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_submissions');
    }
};
