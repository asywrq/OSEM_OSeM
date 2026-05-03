<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compound_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('reason');
            $table->enum('result', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appeals');
    }
};