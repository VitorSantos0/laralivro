<?php

namespace App\Services;

use App\Models\Autor;

class AutorService extends Service
{
    public function create(array $data): Autor
    {
        return Autor::create($data);
    }

    public function update(Autor $autor, array $data): Autor
    {
        $autor->update($data);

        return $autor;
    }

    public function delete(Autor $autor): void
    {
        $this->transactional(
            fn () => $autor->delete(),
            'Este autor(a) está vinculado a um ou mais livros.',
        );
    }
}
