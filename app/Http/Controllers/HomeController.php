<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Requisicao_documento;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware(['auth', 'verified']);
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      //Copie e cole isso na outra rota que for usar ------------->
        if(Auth::check()){
          if(Auth::user()->tipo == 'servidor'){
            $cursos = Curso::all();
            $requisicoes= DB::table('requisicao_documentos')
                             ->join('requisicaos', 'requisicaos.id', '=', 'requisicao_documentos.requisicao_id')
                             ->join('perfils', 'requisicaos.perfil_id', '=', 'perfils.id')
                             ->join('cursos', 'perfils.curso_id', '=' ,'cursos.id')
                             ->select ('requisicao_documentos.id')
                             ->where('status', 'Em andamento')
                             ->whereNull('requisicao_documentos.deleted_at')
                             ->whereNotNull('requisicao_documentos.documento_id')
                             ->groupBy('perfils.curso_id', 'requisicao_documentos.documento_id')
                             ->select('perfils.curso_id', 'requisicao_documentos.documento_id', DB::raw('count(*) as total'))
                             ->get();
            $tipoDocumento = ['Declaração de Vínculo','Comprovante de Matrícula','Histórico','Programa de Disciplina', 'Desbloqueio do Siga', 'Outros','Emitidos', 'Indeferidos'];
            return view('telas_servidor.home_servidor', compact('cursos', 'tipoDocumento', 'requisicoes'));
          }
          else if (Auth::user()->tipo == 'aluno') {
          return view('autenticacao.home-aluno');
          }

          else if (Auth::user()->tipo == 'administrador') {
          return view('autenticacao.home-administrador');
          }

          else if (Auth::user()->tipo == 'bibliotecario' || Auth::user()->tipo == 'analistabibliotecario') {
              return redirect()->route('listar-fichas');
          }
        }
      //
        return view('home');
    }
}
