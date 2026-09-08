<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('nft_balance', 16, 2)->default(0)->after('account_bal');
        });

        Schema::table('nfts', function (Blueprint $table) {
            $table->decimal('projected_min_value', 12, 2)->default(0)->after('price');
            $table->decimal('projected_max_value', 12, 2)->default(0)->after('projected_min_value');
            $table->foreignId('owner_user_id')->nullable()->after('is_available')->constrained('users')->nullOnDelete();
            $table->decimal('purchase_price', 12, 2)->nullable()->after('owner_user_id');
            $table->timestamp('purchased_at')->nullable()->after('purchase_price');
            $table->boolean('auto_bid_enabled')->default(false)->after('purchased_at')->index();
        });

        DB::table('nfts')->where('projected_min_value', 0)->update([
            'projected_min_value' => DB::raw('price'),
            'projected_max_value' => DB::raw('price'),
        ]);

        Schema::create('nft_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nft_id')->constrained('nfts')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 16);
            $table->string('source', 16);
            $table->string('status', 16)->default('active');
            $table->timestamps();
            $table->index(['nft_id', 'status']);
        });

        Schema::create('nft_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('nft_id')->constrained('nfts')->cascadeOnDelete();
            $table->foreignId('nft_bid_id')->nullable()->constrained('nft_bids')->nullOnDelete();
            $table->string('type', 16);
            $table->decimal('amount', 12, 2);
            $table->string('currency', 16);
            $table->decimal('nft_balance_after', 16, 2)->nullable();
            $table->timestamps();
            $table->index(['user_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('nft_transactions');
        Schema::dropIfExists('nft_bids');
        Schema::table('nfts', function (Blueprint $table) {
            $table->dropForeign(['owner_user_id']);
            $table->dropColumn(['projected_min_value', 'projected_max_value', 'owner_user_id', 'purchase_price', 'purchased_at', 'auto_bid_enabled']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nft_balance');
        });
    }
};
