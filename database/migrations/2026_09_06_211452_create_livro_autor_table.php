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
        Schema::create('livro_autor', function (Blueprint $table) {
            $table->unsignedInteger('livro_codl');
            $table->unsignedInteger('autor_codau');

            $table->primary(['livro_codl', 'autor_codau']);

            $table->foreign('livro_codl')->references('codl')->on('livro')->onDelete('restrict');
            $table->foreign('autor_codau')->references('codau')->on('autor')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_autor');
    }
};
