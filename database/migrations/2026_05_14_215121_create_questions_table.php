<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('explanation')->nullable();
            $table->enum('input_type', ['text', 'number', 'email', 'phone', 'date', 'textarea', 'select', 'radio', 'checkbox']);
            $table->json('options')->nullable();
            $table->boolean('required')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
