<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;

use App\Services\AssuntoService;
use App\Services\AutorService;
use App\Services\LivroService;

class LivroController extends Controller
{
    public function __construct(
        protected LivroService $livroService,
        protected AutorService $autorService,
        protected AssuntoService $assuntoService,
    ) {
    }

    public function index()
    {
        $livros = $this->livroService->getAllLivros();
        return view('livros.index', compact('livros'));
    }

    public function create()
    {
        $autores = $this->autorService->getAllAutores();
        $assuntos = $this->assuntoService->getAllAssuntos();
        return view('livros.create', compact('autores', 'assuntos'));
    }

    public function store(LivroRequest $request)
    {
        $this->livroService->createLivro($request->validated());
        return redirect()->route('livros.index')->with('success', 'Livro cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $livro = $this->livroService->getLivroById($id);
        $autores = $this->autorService->getAllAutores();
        $assuntos = $this->assuntoService->getAllAssuntos();
        return view('livros.edit', compact('livro', 'autores', 'assuntos'));
    }

    public function update(LivroRequest $request, $id)
    {
        $livro = $this->livroService->getLivroById($id);
        $this->livroService->updateLivro($livro, $request->validated());
        return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $livro = $this->livroService->getLivroById($id);
        $this->livroService->deleteLivro($livro);
        return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso.');
    }
}
