<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AutorController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\AssuntoController;
use App\Http\Controllers\RelatorioController;

Route::view('/', 'index')->name('home');

Route::get('/autores', [AutorController::class, 'index'])->name('autores.index');
Route::get('/autores/create', [AutorController::class, 'create'])->name('autores.create');
Route::post('/autores', [AutorController::class, 'store'])->name('autores.store');
Route::get('/autores/{autor}/edit', [AutorController::class, 'edit'])->name('autores.edit');
Route::put('/autores/{autor}', [AutorController::class, 'update'])->name('autores.update');
Route::delete('/autores/{autor}', [AutorController::class, 'destroy'])->name('autores.destroy');

Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
Route::get('/livros/create', [LivroController::class, 'create'])->name('livros.create');
Route::post('/livros', [LivroController::class, 'store'])->name('livros.store');
Route::get('/livros/{livro}/edit', [LivroController::class, 'edit'])->name('livros.edit');
Route::put('/livros/{livro}', [LivroController::class, 'update'])->name('livros.update');
Route::delete('/livros/{livro}', [LivroController::class, 'destroy'])->name('livros.destroy');

Route::get('/assuntos', [AssuntoController::class, 'index'])->name('assuntos.index');
Route::get('/assuntos/create', [AssuntoController::class, 'create'])->name('assuntos.create');
Route::post('/assuntos', [AssuntoController::class, 'store'])->name('assuntos.store');
Route::get('/assuntos/{assunto}/edit', [AssuntoController::class, 'edit'])->name('assuntos.edit');
Route::put('/assuntos/{assunto}', [AssuntoController::class, 'update'])->name('assuntos.update');
Route::delete('/assuntos/{assunto}', [AssuntoController::class, 'destroy'])->name('assuntos.destroy');

Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
Route::get('/relatorios/livros-por-autor', [RelatorioController::class, 'livrosPorAutor'])->name('relatorios.livros-por-autor');
Route::get('/relatorios/livros-por-autor/pdf', [RelatorioController::class, 'livrosPorAutorPdf'])->name('relatorios.livros-por-autor.pdf');
