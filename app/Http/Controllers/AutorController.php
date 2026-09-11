<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorRequest;
use App\Services\AutorServiceInterface;

class AutorController extends Controller
{
    public function __construct(private readonly AutorServiceInterface $autorService)
    {
    }

    public function index()
    {
        $autores = $this->autorService->all();
        return view('autores.index', compact('autores'));
    }

    public function create()
    {
        return view('autores.create');
    }

    public function store(AutorRequest $request)
    {
        $this->autorService->create($request->validated());
        return redirect()->route('autores.index')->with('success', 'Autor cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $autor = $this->autorService->findOrFail($id);
        return view('autores.edit', compact('autor'));
    }

    public function update(AutorRequest $request, $id)
    {
        $autor = $this->autorService->findOrFail($id);
        $this->autorService->update($autor, $request->validated());
        return redirect()->route('autores.index')->with('success', 'Autor atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $autor = $this->autorService->findOrFail($id);
        $this->autorService->delete($autor);
        return redirect()->route('autores.index')->with('success', 'Autor excluído com sucesso.');
    }
}
