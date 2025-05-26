<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // التحقق أولاً إذا كان الجدول موجوداً
        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('token');
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        } 
        // إذا كان الجدول موجوداً ولكن يحتاج لتحديث
        else {
            // فحص إذا لم تكن الأعمدة المطلوبة موجودة
            Schema::table('password_resets', function (Blueprint $table) {
                if (!Schema::hasColumn('password_resets', 'id')) {
                    $table->id();
                }
                if (!Schema::hasColumn('password_resets', 'user_id')) {
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                }
                if (!Schema::hasColumn('password_resets', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable();
                }
                if (!Schema::hasColumn('password_resets', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لن نحذف الجدول حتى لا نفقد البيانات
    }
};