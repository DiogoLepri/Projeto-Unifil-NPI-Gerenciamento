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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hackathon_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->foreignId('lider_id')->constrained('users')->onDelete('cascade');
            $table->string('projeto_nome')->nullable();
            $table->string('projeto_arquivo')->nullable();
            $table->enum('status', ['Aberto', 'Fechado'])->default('Aberto');
            $table->text('feedback')->nullable();
            $table->decimal('nota', 3, 1)->nullable();
            $table->boolean('validado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};