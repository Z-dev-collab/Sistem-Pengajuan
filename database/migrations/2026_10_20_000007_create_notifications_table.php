<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('channel')->default('whatsapp');
            $table->string('recipient');
            $table->text('message');
            $table->string('status')->default('pending')->index();
            $table->string('provider_reference')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
