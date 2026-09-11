<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuntoRequest;
use App\Models\Assunto;
use App\Services\AssuntoService;

class AssuntoController extends Controller
{
    public function __construct(private readonly AssuntoService $assuntoService)
    {
    }

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
        $this->assuntoService->create($request->validated());
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
        $this->assuntoService->update($assunto, $request->validated());
        return redirect()->route('assuntos.index')->with('success', 'Assunto atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $assunto = Assunto::findOrFail($id);
        $this->assuntoService->delete($assunto);
        return redirect()->route('assuntos.index')->with('success', 'Assunto excluído com sucesso.');
    }
}
