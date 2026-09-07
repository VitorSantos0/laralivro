<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Autor;
use App\Models\Assunto;
use App\Models\Livro;

class LivroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $machado = Autor::where('nome', 'Machado de Assis')->first();
        $clarice = Autor::where('nome', 'Clarice Lispector')->first();
        $ilana = Autor::where('nome', 'Ilana Casoy')->first();
        $raphael = Autor::where('nome', 'Raphael Montes')->first();
        $jorge = Autor::where('nome', 'Jorge Amado')->first();

        $romance = Assunto::where('descricao', 'Romance')->first();
        $ficcao = Assunto::where('descricao', 'Ficção')->first();
        $suspense = Assunto::where('descricao', 'Suspense')->first();
        $litBrasileira = Assunto::where('descricao', 'Lit. Brasileira')->first();
        $trueCrime = Assunto::where('descricao', 'True Crime')->first();

        $domCasmurro = Livro::create([
            'titulo' => 'Dom Casmurro',
            'editora' => 'Editora Ática',
            'edicao' => 3,
            'ano_publicacao' => 1899,
            'valor' => 39.90,
        ]);
        $domCasmurro->autores()->attach($machado->codau);
        $domCasmurro->assuntos()->attach([$romance->codas, $litBrasileira->codas]);

        $horaEstrela = Livro::create([
            'titulo' => 'A Hora da Estrela',
            'editora' => 'Rocco',
            'edicao' => 1,
            'ano_publicacao' => 1977,
            'valor' => 34.50,
        ]);
        $horaEstrela->autores()->attach($clarice->codau);
        $horaEstrela->assuntos()->attach([$ficcao->codas, $litBrasileira->codas]);

        $bomDiaVeronica = Livro::create([
            'titulo' => 'Bom Dia, Verônica',
            'editora' => 'Companhia das Letras',
            'edicao' => 2,
            'ano_publicacao' => 2022,
            'valor' => 52.40,
        ]);
        $bomDiaVeronica->autores()->attach([$ilana->codau, $raphael->codau]);
        $bomDiaVeronica->assuntos()->attach([$suspense->codas, $trueCrime->codas]);

        $gabriela = Livro::create([
            'titulo' => 'Gabriela, Cravo e Canela',
            'editora' => 'Companhia das Letras',
            'edicao' => 5,
            'ano_publicacao' => 1958,
            'valor' => 45.00,
        ]);
        $gabriela->autores()->attach($jorge->codau);
        $gabriela->assuntos()->attach([$romance->codas, $litBrasileira->codas]);
    }
}
