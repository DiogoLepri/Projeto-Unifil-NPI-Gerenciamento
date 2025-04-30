<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('projetos', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('unidade_curricular');
        $table->string('codigo')->unique();
        $table->integer('bimestre');
        $table->foreignId('professor_id')->constrained('users');
        $table->enum('status', ['aberto', 'fechado'])->default('aberto');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projetos');
    }
};
