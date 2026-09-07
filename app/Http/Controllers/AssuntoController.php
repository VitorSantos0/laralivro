<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuntoRequest;

use App\Models\Assunto;
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
        return redirect()->route('assuntos.index');
    }

    public function edit(Assunto $assunto)
    {
        return view('assuntos.edit', compact('assunto'));
    }

    public function update(AssuntoRequest $request, $id)
    {
        $assunto = Assunto::findOrFail($id);
        $this->assuntoService->updateAssunto($assunto, $request->validated());
        return redirect()->route('assuntos.index');
    }

    public function destroy($id)
    {
        $assunto = Assunto::findOrFail($id);
        $this->assuntoService->deleteAssunto($assunto);
        return redirect()->route('assuntos.index');
    }
}
