<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Concerns\HasCompositePrimaryKey;

class LivroAutor extends Model
{
    use HasCompositePrimaryKey;
    
    protected $table = 'livro_autor';
    protected $primaryKey = ['livro_codl', 'autor_codau'];

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['livro_codl', 'autor_codau'];
}
