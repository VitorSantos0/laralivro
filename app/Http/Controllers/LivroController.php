<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;
use App\Services\LivroServiceInterface;

class LivroController extends Controller
{
    public function __construct(private readonly LivroServiceInterface $livroService)
    {
    }

    public function index()
    {
        $livros = $this->livroService->all();
        return view('livros.index', compact('livros'));
    }

    public function create()
    {
        return view('livros.create', $this->livroService->formOptions());
    }

    public function store(LivroRequest $request)
    {
        $this->livroService->create($request->validated());
        return redirect()->route('livros.index')->with('success', 'Livro cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $livro = $this->livroService->findOrFail($id);
        $options = $this->livroService->formOptions();
        return view('livros.edit', ['livro' => $livro] + $options);
    }

    public function update(LivroRequest $request, $id)
    {
        $livro = $this->livroService->findOrFail($id);
        $this->livroService->update($livro, $request->validated());
        return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $livro = $this->livroService->findOrFail($id);
        $this->livroService->delete($livro);
        return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso.');
    }
}
