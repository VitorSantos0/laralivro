<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Assunto;

class AssuntoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Assunto::create(['descricao' => 'Romance']);
        Assunto::create(['descricao' => 'Ficção']);
        Assunto::create(['descricao' => 'Suspense']);
        Assunto::create(['descricao' => 'Lit. Brasileira']);
        Assunto::create(['descricao' => 'True Crime']);
    }
}
