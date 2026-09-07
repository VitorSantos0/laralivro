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

    public function getAutorById(int $id)
    {
        return Autor::findOrFail($id);
    }

    public function createAutor(array $data)
    {
        return Autor::create($data);
    }

    public function updateAutor(int $id, array $data)
    {
        $autor = Autor::findOrFail($id);
        $autor->update($data);
        return $autor;
    }

    public function deleteAutor(int $id)
    {
        $autor = Autor::findOrFail($id);
        try {
            DB::transaction(function () use ($autor) {
                $autor->delete();
            });
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                throw new RegistroVinculadoException('Este autor(a) está vinculado a um ou mais livros.');
            }
            throw $e;
        }
    }
}