<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role_in_class', ['monitor'])->default('monitor');
            $table->timestamps();

            $table->unique(['class_id', 'user_id']);
            $table->index(['user_id', 'role_in_class']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_user');
    }
};
