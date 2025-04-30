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
        Schema::table('users', function (Blueprint $table) {
            // Check if the columns don't exist before adding them
            if (!Schema::hasColumn('users', 'matricula')) {
                $table->string('matricula')->unique()->after('password');
            }
            
            if (!Schema::hasColumn('users', 'curso')) {
                $table->string('curso')->after('matricula');
            }
            
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['aluno', 'professor'])->default('aluno')->after('curso');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['matricula', 'curso', 'role']);
        });
    }
};