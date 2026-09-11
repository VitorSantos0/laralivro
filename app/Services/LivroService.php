<?php

namespace App\Services;

use App\Models\Livro;

class LivroService extends Service
{
    public function create(array $data): Livro
    {
        return $this->transactional(function () use ($data) {
            $livro = Livro::create($data);
            $livro->autores()->sync($data['autores']);
            $livro->assuntos()->sync($data['assuntos']);

            return $livro;
        });
    }

    public function update(Livro $livro, array $data): Livro
    {
        return $this->transactional(function () use ($livro, $data) {
            $livro->update($data);
            $livro->autores()->sync($data['autores']);
            $livro->assuntos()->sync($data['assuntos']);

            return $livro;
        });
    }

    public function delete(Livro $livro): void
    {
        $this->transactional(function () use ($livro) {
            $livro->autores()->detach();
            $livro->assuntos()->detach();
            $livro->delete();
        });
    }
}
