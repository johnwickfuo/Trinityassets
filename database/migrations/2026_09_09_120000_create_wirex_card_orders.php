<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->string('purpose', 40)->nullable()->after('payment_mode')->index();
            $table->string('purpose_reference', 40)->nullable()->after('purpose');
            $table->unique(['purpose', 'purpose_reference'], 'deposits_purpose_reference_unique');
        });

        Schema::create('wirex_card_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('deposit_id')->nullable()->unique()->constrained('deposits')->nullOnDelete();
            $table->string('reference', 32)->unique();
            $table->string('card_code', 32)->nullable()->unique();
            $table->string('currency_code', 8);
            $table->string('currency_symbol', 12);
            $table->decimal('card_fee', 16, 2)->default(1700);
            $table->decimal('activation_fee', 16, 2)->default(1700);
            $table->decimal('total_fee', 16, 2)->default(3400);
            $table->string('status', 24)->default('awaiting_payment')->index();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wirex_card_orders');
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropIndex(['purpose']);
            $table->dropUnique('deposits_purpose_reference_unique');
            $table->dropColumn(['purpose', 'purpose_reference']);
        });
    }
};
