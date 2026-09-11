<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

class LivroAutor extends Model
{
    use AsPivot;

    protected $table = 'livro_autor';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['livro_codl', 'autor_codau'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setPivotKeys('livro_codl', 'autor_codau');
    }
}
