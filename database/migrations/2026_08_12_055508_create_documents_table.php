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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان الوثيقة
            $table->string('document_number')->unique()->nullable(); // رقم الوثيقة / الصادر
            $table->foreignId('department_id')->constrained()->cascadeOnDelete(); // القسم التابع له
            $table->foreignId('category_id')->constrained()->cascadeOnDelete(); // التصنيف
            $table->string('attachment')->nullable(); // ملف الوثيقة المرفق
            $table->date('document_date')->nullable(); // تاريخ الوثيقة
            $table->date('expiry_date')->nullable(); // تاريخ انتهاء الوثيقة (للعقود والتراخيص)
            $table->text('description')->nullable(); // ملاحظات أو ملخص
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // الموظف الذي أرشفها
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
