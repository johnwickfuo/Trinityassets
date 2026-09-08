<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('withdrawal_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->boolean('is_blocked')->default(false)->index();
            $table->text('message')->nullable();
            $table->unsignedBigInteger('updated_by_admin_id')->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->timestamp('unblocked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('withdrawal_restrictions');
    }
};
