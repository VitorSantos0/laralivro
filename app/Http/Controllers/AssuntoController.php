<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssuntoController extends Controller
{
    public function index()
    {
        return view('assuntos.index');
    }
}
