<?php

namespace App\Http\Controllers;

use App\Exceptions\RegistroVinculadoException;
use App\Http\Requests\AssuntoRequest;
use App\Models\Assunto;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AssuntoController extends Controller
{
    public function index()
    {
        $assuntos = Assunto::all();
        return view('assuntos.index', compact('assuntos'));
    }

    public function create()
    {
        return view('assuntos.create');
    }

    public function store(AssuntoRequest $request)
    {
        Assunto::create($request->validated());
        return redirect()->route('assuntos.index')->with('success', 'Assunto cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $assunto = Assunto::findOrFail($id);
        return view('assuntos.edit', compact('assunto'));
    }

    public function update(AssuntoRequest $request, $id)
    {
        $assunto = Assunto::findOrFail($id);
        $assunto->update($request->validated());
        return redirect()->route('assuntos.index')->with('success', 'Assunto atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $assunto = Assunto::findOrFail($id);

        try {
            DB::transaction(function () use ($assunto) {
                $assunto->delete();
            });
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                throw new RegistroVinculadoException('Este assunto está vinculado a um ou mais livros.');
            }
            throw $e;
        }

        return redirect()->route('assuntos.index')->with('success', 'Assunto excluído com sucesso.');
    }
}
