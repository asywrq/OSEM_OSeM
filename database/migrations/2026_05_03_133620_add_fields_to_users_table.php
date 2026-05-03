<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('matric_or_staff_no')->unique()->after('name');
            $table->enum('role', ['admin', 'officer', 'user'])->default('user')->after('matric_or_staff_no');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['matric_or_staff_no', 'role', 'is_active']);
        });
    }
};