<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline');
            $table->text('description');
            $table->string('icon')->default('document');
            $table->string('keywords')->nullable();
            $table->unsignedInteger('fee')->default(0);
            $table->string('fee_note')->nullable();
            $table->unsignedSmallInteger('estimated_days')->default(3);
            $table->string('form_title')->nullable();
            $table->json('form_fields')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
