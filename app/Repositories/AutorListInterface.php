<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface AutorListInterface
{
    public function all(): Collection;
}
