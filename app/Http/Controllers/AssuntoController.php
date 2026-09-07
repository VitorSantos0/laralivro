<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuntoRequest;

use App\Services\AssuntoService;

class AssuntoController extends Controller
{
    public function __construct(protected AssuntoService $assuntoService)
    {
    }

    public function index()
    {
        $assuntos = $this->assuntoService->getAllAssuntos();
        return view('assuntos.index', compact('assuntos'));
    }

    public function create()
    {
        return view('assuntos.create');
    }

    public function store(AssuntoRequest $request)
    {
        $this->assuntoService->createAssunto($request->validated());
        return redirect()->route('assuntos.index')->with('success', 'Assunto cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $assunto = $this->assuntoService->getAssuntoById($id);
        return view('assuntos.edit', compact('assunto'));
    }

    public function update(AssuntoRequest $request, $id)
    {
        $assunto = $this->assuntoService->getAssuntoById($id);
        $this->assuntoService->updateAssunto($assunto, $request->validated());
        return redirect()->route('assuntos.index')->with('success', 'Assunto atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $assunto = $this->assuntoService->getAssuntoById($id);
        $this->assuntoService->deleteAssunto($assunto);
        return redirect()->route('assuntos.index')->with('success', 'Assunto excluído com sucesso.');
    }
}
