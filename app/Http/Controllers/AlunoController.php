<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlunoRequest;
use App\Models\FichaCatalografica;
use App\Models\NadaConsta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Curso;
use App\Models\Aluno;
use App\Models\User;
use App\Models\Perfil;
use App\Models\Unidade;
use App\Models\Servidor;
use App\Models\Requisicao;
use App\Models\Documento;
use App\Models\Requisicao_documento;

class AlunoController extends Controller
{
  // Redireciona para tela de login ao entrar no sistema
  public function index()
  {
    // return view('autenticacao.home-aluno');
    if(Auth::user()){
      return redirect()->route('home-aluno');
    }
    return view('autenticacao.login');
  }
  //redireciona para a lista de requisições do aluno
  //devolve para a view a lista de requisicoes que o aluno fez
  public function listarRequisicoes(){
    $idUser=Auth::user()->id;
    $aluno = Aluno::where('user_id',$idUser)->first();
    //ordena pela data e hora do pedido
    // $requisicoes = Requisicao::where('aluno_id',$aluno->id)->orderBy('data_pedido','desc')->orderBy('hora_pedido', 'desc')->get();
    $requisicoes = Requisicao::where('aluno_id',$aluno->id)->orderBy('id','desc')->get();
    $requisicoes_documentos = Requisicao_documento::where('aluno_id',$aluno->id)->get();
    $aluno= Aluno::where('user_id',$idUser)->first();
    $documentos = Documento::all();
    $fichas = FichaCatalografica::all();
    $perfis = Perfil::where('aluno_id',$aluno->id)->get();


    return view('telas_aluno.requisicoes_aluno',compact('requisicoes','requisicoes_documentos','aluno','documentos','perfis','fichas'));
  }
  public function homeAluno(){
    return view('autenticacao.home-aluno');
  }
  //cadastro de aluno
  public function createAluno(){
    $cursos = Curso::where('id', '=', '0')->get();
    $unidades = Unidade::all();
    // $usuario = User::find(Auth::user()->id);
    $perfis = Perfil::all();
    return view('autenticacao.cadastro',compact('cursos','unidades','perfis')); //redireciona para view de cadastro do aluno
  }
  public function storeAluno(StoreAlunoRequest $request){

    DB::beginTransaction();
    $user = User::create([ //Criação de usuário, para apenas após a criação ser atribuida a nova variável
                          'name' => mb_strtoupper($request['name']),
                          'email' => $request['email'],
                          'password' => Hash::make($request['password']),
                          'tipo' => 'aluno',
                       ]);

    $aluno = Aluno::create([
                          'user_id'=>$user->id,
                          'cpf' => str_replace(['.','-'], '', $request['cpf']),
                          ]);
    $id = Curso::where('id', $request['cursos'])->first();
    $curso = $id->nome;

    $vinculo = $request['vinculo'];
        if($vinculo==="1"){

          $situacao = "Matriculado";
        }else if ($vinculo==="2"){

          $situacao = "Egresso";
        }
        else if ($vinculo==="3"){

          $situacao = "Especial";
        }
        else if ($vinculo==="4"){
          $situacao = "REMT - Regime Especial de Movimentação Temporária";
        }
        else if ($vinculo==="5"){
          $situacao = "Desistente";
        }
        else if ($vinculo==="6"){
          $situacao = "Trancado";
        }
        else if ($vinculo==="7"){
          $situacao = "Intercâmbio";
        }
    $perfil = Perfil::create([
                        'default' => $curso,
                        'situacao' => $situacao,
                        'valor' =>true,
                        'aluno_id' => $aluno->id,
                        'curso_id' => $request['cursos'],
                        'unidade_id' => $request['unidade'],
                        ]);

    if(!$user || !$aluno){
      DB::rollBack();
      return  redirect('/')->with(['error' => 'Não foi possível Criar.']);
    }
    DB::commit();



    Auth::login($user);

    $user->sendEmailVerificationNotification();

    //para verificar email logo apos cadastro
      return redirect()->route('verification.notice');
      //return redirect('home-aluno')->with('success', 'Cadastrado com sucesso!');

  }
  public function home(){
    return view ('autenticacao.home-aluno');
  }

  public function loadCursos($id) {
    $cursos = Curso::where('unidade_id', $id)->get();
    return view('autenticacao.cursos', compact('cursos'));
  }
}
