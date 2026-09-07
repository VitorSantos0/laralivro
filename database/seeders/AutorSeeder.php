<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Autor;

class AutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Autor::create(['nome' => 'Machado de Assis']);
        Autor::create(['nome' => 'Clarice Lispector']);
        Autor::create(['nome' => 'Ilana Casoy']);
        Autor::create(['nome' => 'Raphael Montes']);
        Autor::create(['nome' => 'Jorge Amado']);
    }
}
