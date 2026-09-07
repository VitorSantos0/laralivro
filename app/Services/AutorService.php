<?php

namespace App\Services;

use App\Exceptions\RegistroVinculadoException;
use App\Models\Autor;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AutorService
{
    public function getAllAutores()
    {
        return Autor::all();
    }

    public function createAutor(array $data)
    {
        return Autor::create($data);
    }

    public function updateAutor(Autor $autor, array $data)
    {
        $autor->update($data);
        return $autor;
    }

    public function deleteAutor(Autor $autor)
    {
        try {
            DB::transaction(function () use ($autor) {
                $autor->delete();
            });
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                throw new RegistroVinculadoException('Este autor está vinculado a um ou mais livros.');
            }
            throw $e;
        }
    }
}