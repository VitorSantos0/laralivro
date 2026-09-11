<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use App\Services\LivroService;

class LivroController extends Controller
{
    public function __construct(private readonly LivroService $livroService)
    {
    }

    public function index()
    {
        $livros = Livro::with(['autores', 'assuntos'])->get();
        return view('livros.index', compact('livros'));
    }

    public function create()
    {
        $autores = Autor::all();
        $assuntos = Assunto::all();
        return view('livros.create', compact('autores', 'assuntos'));
    }

    public function store(LivroRequest $request)
    {
        $this->livroService->create($request->validated());
        return redirect()->route('livros.index')->with('success', 'Livro cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $livro = Livro::with(['autores', 'assuntos'])->findOrFail($id);
        $autores = Autor::all();
        $assuntos = Assunto::all();
        return view('livros.edit', compact('livro', 'autores', 'assuntos'));
    }

    public function update(LivroRequest $request, $id)
    {
        $livro = Livro::with(['autores', 'assuntos'])->findOrFail($id);
        $this->livroService->update($livro, $request->validated());
        return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $livro = Livro::with(['autores', 'assuntos'])->findOrFail($id);
        $this->livroService->delete($livro);
        return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso.');
    }
}
