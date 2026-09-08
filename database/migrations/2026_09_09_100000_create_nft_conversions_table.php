<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nft_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('reference', 32)->unique();
            $table->decimal('nft_amount', 16, 2);
            $table->decimal('exchange_rate', 12, 4);
            $table->decimal('converted_amount', 16, 2);
            $table->decimal('fee_percentage', 5, 2)->default(10);
            $table->decimal('fee_amount', 16, 2);
            $table->string('from_currency', 16)->default('USDT');
            $table->string('to_currency', 16)->default('TTD');
            $table->string('status', 16)->default('pending');
            $table->string('fee_status', 16)->default('unpaid');
            $table->timestamp('expires_at')->index();
            $table->timestamp('fee_paid_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('processed_by_admin_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('nft_conversions');
    }
};
