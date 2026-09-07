<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LivroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'min:3', 'max:40'],
            'editora' => ['required', 'string', 'min:3', 'max:40'],
            'edicao' => ['required', 'integer', 'min:1'],
            'ano_publicacao' => ['required', 'integer', 'min:1000', 'max:9999'],
            'valor' => ['required', 'numeric', 'min:0'],
            'autores' => ['required', 'array', 'min:1'],
            'autores.*' => ['integer', 'exists:autor,codau'],
            'assuntos' => ['required', 'array', 'min:1'],
            'assuntos.*' => ['integer', 'exists:assunto,codas'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título do livro é obrigatório.',
            'titulo.min' => 'O título deve ter pelo menos :min caracteres.',
            'titulo.max' => 'O título não pode ultrapassar :max caracteres.',
            'editora.required' => 'A editora do livro é obrigatória.',
            'editora.min' => 'A editora deve ter pelo menos :min caracteres.',
            'editora.max' => 'A editora não pode ultrapassar :max caracteres.',
            'edicao.required' => 'A edição do livro é obrigatória.',
            'edicao.integer' => 'A edição deve ser um número inteiro.',
            'edicao.min' => 'A edição deve ser pelo menos :min.',
            'ano_publicacao.required' => 'O ano de publicação é obrigatório.',
            'ano_publicacao.integer' => 'O ano de publicação deve ser um número inteiro.',
            'valor.required' => 'O valor do livro é obrigatório.',
            'valor.numeric' => 'O valor do livro deve ser numérico.',
            'valor.min' => 'O valor do livro não pode ser negativo.',
            'autores.required' => 'Selecione ao menos um autor.',
            'autores.min' => 'Selecione ao menos um autor.',
            'autores.*.exists' => 'Um dos autores selecionados é inválido.',
            'assuntos.required' => 'Selecione ao menos um assunto.',
            'assuntos.min' => 'Selecione ao menos um assunto.',
            'assuntos.*.exists' => 'Um dos assuntos selecionados é inválido.',
        ];
    }
}
