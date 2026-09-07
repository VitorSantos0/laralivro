<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Concerns\HasCompositePrimaryKey;

class LivroAssunto extends Model
{
    use HasCompositePrimaryKey;

    protected $table = 'livro_assunto';
    protected $primaryKey = ['livro_codl', 'assunto_codas'];
    
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['livro_codl', 'assunto_codas'];
}
