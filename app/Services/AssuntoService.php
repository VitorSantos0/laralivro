<?php

namespace App\Services;

use App\Exceptions\RegistroVinculadoException;
use App\Models\Assunto;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AssuntoService
{
    public function getAllAssuntos()
    {
        return Assunto::all();
    }

    public function createAssunto(array $data)
    {
        return Assunto::create($data);
    }

    public function updateAssunto(Assunto $assunto, array $data)
    {
        $assunto->update($data);
        return $assunto;
    }

    public function deleteAssunto(Assunto $assunto)
    {
        try {
            DB::transaction(function () use ($assunto) {
                $assunto->delete();
            });
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                throw new RegistroVinculadoException('Este assunto está vinculado a um ou mais livros.');
            }
            throw $e;
        }
    }
}
