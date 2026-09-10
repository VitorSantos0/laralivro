<?php

namespace App\Http\Controllers;

use App\Exceptions\RegistroVinculadoException;
use App\Http\Requests\AutorRequest;
use App\Models\Autor;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AutorController extends Controller
{
    public function index()
    {
        $autores = Autor::all();
        return view('autores.index', compact('autores'));
    }

    public function create()
    {
        return view('autores.create');
    }

    public function store(AutorRequest $request)
    {
        Autor::create($request->validated());
        return redirect()->route('autores.index')->with('success', 'Autor cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $autor = Autor::findOrFail($id);
        return view('autores.edit', compact('autor'));
    }

    public function update(AutorRequest $request, $id)
    {
        $autor = Autor::findOrFail($id);
        $autor->update($request->validated());
        return redirect()->route('autores.index')->with('success', 'Autor atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $autor = Autor::findOrFail($id);

        try {
            DB::transaction(function () use ($autor) {
                $autor->delete();
            });
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                throw new RegistroVinculadoException('Este autor(a) está vinculado a um ou mais livros.');
            }
            throw $e;
        }

        return redirect()->route('autores.index')->with('success', 'Autor excluído com sucesso.');
    }
}
