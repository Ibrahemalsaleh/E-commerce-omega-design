<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token')->unique()->index(); // إضافة فهرس وتفرد
            $table->timestamp('expires_at')->index(); // إضافة فهرس
            $table->boolean('used')->default(false); // إضافة عمود used بقيمة افتراضية false
            $table->timestamps(); // إضافة created_at و updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};