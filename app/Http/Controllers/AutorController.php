<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorRequest;

use App\Services\AutorService;

class AutorController extends Controller
{
    public function __construct(protected AutorService $autorService)
    {
    }

    public function index()
    {
        $autores = $this->autorService->getAllAutores();
        return view('autores.index', compact('autores'));
    }

    public function create()
    {
        return view('autores.create');
    }

    public function store(AutorRequest $request)
    {
        $this->autorService->createAutor($request->validated());
        return redirect()->route('autores.index');
    }

    public function edit($id)
    {
        $autor = $this->autorService->getAutorById($id);
        return view('autores.edit', compact('autor'));
    }

    public function update(AutorRequest $request, $id)
    {
        $this->autorService->updateAutor($id, $request->validated());
        return redirect()->route('autores.index');
    }

    public function destroy($id)
    {
        $this->autorService->deleteAutor($id);
        return redirect()->route('autores.index');
    }
}
