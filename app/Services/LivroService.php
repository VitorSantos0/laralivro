<?php

namespace App\Services;

use App\Models\Livro;

use Illuminate\Support\Facades\DB;

class LivroService
{
    public function getAllLivros()
    {
        return Livro::with(['autores', 'assuntos'])->get();
    }

    public function getLivroById(int $id)
    {
        return Livro::with(['autores', 'assuntos'])->findOrFail($id);
    }

    public function createLivro(array $data)
    {
        return DB::transaction(function () use ($data) {
            $livro = Livro::create($data);
            $livro->autores()->sync($data['autores']);
            $livro->assuntos()->sync($data['assuntos']);
            return $livro;
        });
    }

    public function updateLivro(Livro $livro, array $data)
    {
        return DB::transaction(function () use ($livro, $data) {
            $livro->update($data);
            $livro->autores()->sync($data['autores']);
            $livro->assuntos()->sync($data['assuntos']);
            return $livro;
        });
    }

    public function deleteLivro(Livro $livro)
    {
        DB::transaction(function () use ($livro) {
            $livro->autores()->detach();
            $livro->assuntos()->detach();
            $livro->delete();
        });
    }
}
