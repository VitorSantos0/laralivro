<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\AutorRequest;

use App\Models\Autor;
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

    public function edit(Autor $autor)
    {
        return view('autores.edit', compact('autor'));
    }

    public function update(AutorRequest $request, $id)
    {
        $autor = Autor::findOrFail($id);
        $this->autorService->updateAutor($autor, $request->validated());
        return redirect()->route('autores.index');
    }

    public function destroy($id)
    {
        $autor = Autor::findOrFail($id);
        $this->autorService->deleteAutor($autor);
        return redirect()->route('autores.index');
    }
}
