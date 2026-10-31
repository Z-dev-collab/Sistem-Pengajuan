<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code', 24)->unique();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->string('applicant_name');
            $table->string('nik', 16);
            $table->string('kk_number', 16);
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->text('address');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->text('notes')->nullable();
            $table->string('status')->default('submitted')->index();
            $table->string('notification_channel')->default('whatsapp');
            $table->text('admin_notes')->nullable();
            $table->string('result_file')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
