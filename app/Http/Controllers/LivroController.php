<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;

use Illuminate\Support\Facades\DB;

class LivroController extends Controller
{
    public function index()
    {
        $livros = Livro::with(['autores', 'assuntos'])->get();
        return view('livros.index', compact('livros'));
    }

    public function create()
    {
        $autores = Autor::all();
        $assuntos = Assunto::all();
        return view('livros.create', compact('autores', 'assuntos'));
    }

    public function store(LivroRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $livro = Livro::create($data);
            $livro->autores()->sync($data['autores']);
            $livro->assuntos()->sync($data['assuntos']);
        });

        return redirect()->route('livros.index')->with('success', 'Livro cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $livro = Livro::with(['autores', 'assuntos'])->findOrFail($id);
        $autores = Autor::all();
        $assuntos = Assunto::all();
        return view('livros.edit', compact('livro', 'autores', 'assuntos'));
    }

    public function update(LivroRequest $request, $id)
    {
        $livro = Livro::with(['autores', 'assuntos'])->findOrFail($id);
        $data = $request->validated();

        DB::transaction(function () use ($livro, $data) {
            $livro->update($data);
            $livro->autores()->sync($data['autores']);
            $livro->assuntos()->sync($data['assuntos']);
        });

        return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $livro = Livro::with(['autores', 'assuntos'])->findOrFail($id);

        DB::transaction(function () use ($livro) {
            $livro->autores()->detach();
            $livro->assuntos()->detach();
            $livro->delete();
        });

        return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso.');
    }
}
