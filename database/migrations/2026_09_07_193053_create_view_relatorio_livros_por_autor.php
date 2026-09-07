<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW vw_relatorio_livros_por_autor AS
            SELECT
                a.codau AS cod_autor,
                a.nome AS autor,
                l.codl AS cod_livro,
                l.titulo AS livro,
                l.editora,
                l.edicao,
                l.ano_publicacao,
                l.valor,
                assuntos_por_livro.assuntos
            FROM autor a
            INNER JOIN livro_autor la ON la.autor_codau = a.codau
            INNER JOIN livro l ON l.codl = la.livro_codl
            INNER JOIN (
                SELECT las.livro_codl,
                    string_agg(s.descricao, ', ' ORDER BY s.descricao) AS assuntos
                FROM livro_assunto las
                JOIN assunto s ON s.codas = las.assunto_codas
                GROUP BY las.livro_codl
            ) AS assuntos_por_livro ON assuntos_por_livro.livro_codl = l.codl
            ORDER BY a.nome, l.titulo
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_relatorio_livros_por_autor');
    }
};
