<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckBibliotecarioOuAnalista
{
    public function handle($request, Closure $next)
    {
        if(!Auth::check()){
            return redirect('/')->with('error', 'É necessário estar logado.');
        }

        // Permite se o tipo for bibliotecario OU analistabibliotecario
        $tipo = Auth::user()->tipo;
        if($tipo == 'bibliotecario' || $tipo == 'analistabibliotecario'){
            return $next($request);
        }

        return redirect('home')->with('error', 'Você não possui privilégios para esta funcionalidade.');
    }
}