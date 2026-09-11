<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface AssuntoListInterface
{
    public function all(): Collection;
}
