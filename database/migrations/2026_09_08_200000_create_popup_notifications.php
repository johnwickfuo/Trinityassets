<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('popup_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('title', 150);
            $table->text('message');
            $table->string('action_type', 20)->nullable();
            $table->timestamp('expires_at')->index();
            $table->timestamps();
        });

        Schema::create('popup_notification_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('popup_notification_id')->constrained('popup_notifications')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();
            $table->unique(['popup_notification_id', 'user_id'], 'popup_recipient_unique');
            $table->index(['user_id', 'dismissed_at'], 'popup_recipient_status_index');
        });
    }

    public function down()
    {
        Schema::dropIfExists('popup_notification_recipients');
        Schema::dropIfExists('popup_notifications');
    }
};
