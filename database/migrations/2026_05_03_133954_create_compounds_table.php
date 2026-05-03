<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('offence_type_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['unpaid', 'appealing', 'paid', 'resolved'])->default('unpaid');
            $table->boolean('is_discounted')->default(false);
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compounds');
    }
};