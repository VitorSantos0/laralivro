<?php

namespace App\Services;

use App\Models\Assunto;

class AssuntoService extends Service
{
    public function create(array $data): Assunto
    {
        return Assunto::create($data);
    }

    public function update(Assunto $assunto, array $data): Assunto
    {
        $assunto->update($data);

        return $assunto;
    }

    public function delete(Assunto $assunto): void
    {
        $this->transactional(
            fn () => $assunto->delete(),
            'Este assunto está vinculado a um ou mais livros.',
        );
    }
}
