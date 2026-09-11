<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

class LivroAssunto extends Model
{
    use AsPivot;

    protected $table = 'livro_assunto';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['livro_codl', 'assunto_codas'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setPivotKeys('livro_codl', 'assunto_codas');
    }
}
