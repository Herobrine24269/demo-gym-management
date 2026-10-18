<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('called_by')->constrained('users')->restrictOnDelete();
            $table->enum('outcome', ['answered', 'no_answer', 'will_return', 'cancelled']);
            $table->text('notes')->nullable();
            $table->timestamp('followed_up_at');
            $table->timestamps();

            $table->index('member_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
