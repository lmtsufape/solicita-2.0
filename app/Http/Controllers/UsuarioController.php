<?php

namespace App\Http\Controllers;

use App\Models\Biblioteca;
use App\Models\Bibliotecario;
use App\Models\Servidor;
use Illuminate\Http\Request;

use App\Models\Curso;
use App\Models\Aluno;
use App\Models\User;
use App\Models\Perfil;
use App\Models\Unidade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Redireciona para tela de login ao entrar no sistema
    public function index()
    {
        return view('autenticacao.login');
    }

    public function listarAlunos()
    {
        $usuarios = User::where('tipo', 'aluno')->get();
        return view('telas_servidor.listar-alunos', compact('usuarios'));
    }

    //cadastro de aluno
    public function createAluno()
    {

        $cursos = Curso::all();
        $unidades = Unidade::all();

        return view('autenticacao.cadastro', compact('cursos', 'unidades')); //redireciona para view de cadastro do aluno
    }


    public function listarUsuario()
    {

        $usuarios = User::all();
        return view('telas_admin.listar-usuarios', compact('usuarios'));

    }

    public function editarUsuario(Request $request)
    {
        $usuario = User::find($request->id_usuario);

        $cursos = Curso::orderBy('nome')->get();
        $unidades = Unidade::orderBy('nome')->get();
        $situacoes = Perfil::SITUACAO_ENUM;
        // dd($unidades->first());

        switch ($usuario->tipo) {
            case "aluno":
                $usuarioEspecifico = Aluno::where('user_id', $usuario->id)->first();
                $perfil = Perfil::where([['aluno_id', $usuarioEspecifico->id], ['valor', true]])->first();
                $unidadeEspecifica = Unidade::where('id', $perfil->unidade_id)->first();
                $cursoEspecifico = Curso::where('id', $perfil->curso_id)->first();
                return view('telas_admin.editar-usuario', compact('usuario', 'usuarioEspecifico', 'perfil', 'cursoEspecifico', 'unidadeEspecifica', 'unidades', 'cursos', 'situacoes'));

            case "bibliotecario":
                $usuarioEspecifico = Bibliotecario::where('user_id', $usuario->id)->first();
                if (!$usuarioEspecifico || !$usuarioEspecifico->biblioteca_id) {
                    return redirect()->route('listar-usuario')->with('error', 'O bibliotecario informado nao possui biblioteca vinculada.');
                }
                $bibliotecaEspecifica = Biblioteca::where('id', $usuarioEspecifico->biblioteca_id)->first();
                return view('telas_admin.editar-usuario', compact('usuario', 'usuarioEspecifico', 'bibliotecaEspecifica'));

            case "servidor":
                $usuarioEspecifico = Servidor::where('user_id', $usuario->id)->first();
                return view('telas_admin.editar-usuario', compact('usuario', 'usuarioEspecifico'));

            case "analistabibliotecario":
                $usuarioEspecifico = Bibliotecario::where('user_id', $usuario->id)->first();
                if (!$usuarioEspecifico || !$usuarioEspecifico->biblioteca_id) {
                    return redirect()->route('listar-usuario')->with('error', 'O analista bibliotecario informado nao possui biblioteca vinculada.');
                }
                $bibliotecaEspecifica = Biblioteca::where('id', $usuarioEspecifico->biblioteca_id)->first();
                return view('telas_admin.editar-usuario', compact('usuario', 'usuarioEspecifico', 'bibliotecaEspecifica'));

            default:
                return view('telas_admin.editar-usuario', compact('usuario'));

        }


    }

    public function atualizarUsuario(Request $request)
    {

        $usuario = User::find($request->id_usuario);

        $validated = $request->validate([
            'name' => 'required|string|max:300',
            'email' => 'required|string|email|max:255',
            'cpf' => $usuario->tipo == 'aluno' ? 'required|string|cpf' : '',
        ]);

        if ($request->has('tipo')) {
            $request->validate([
                'tipo' => 'in:bibliotecario,analistabibliotecario',
            ]);
        }

        if ($request->email != $usuario->email) {
            $userCheckEmail = User::where('email', 'ilike',$request->email)->first();
            if ($userCheckEmail != null) {
                return redirect()->back()->withErrors(['email' => 'Esse email já está sendo utilizado.'])->withInput($validated);
            }
        }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        if ($request->has('tipo')) {
            $usuario->tipo = $request->tipo;
        }
        $usuario->update();

        if ($usuario->aluno) {
            $aluno = $usuario->aluno;
            if ($request->cpf) {
                $aluno->cpf = $request->cpf;
                $aluno->update();
            }
            $unidade = Unidade::find($request->unidade);
            $curso = Curso::find($request->curso);
            $perfil = Perfil::where([['aluno_id', $aluno->id], ['valor', true]])->first();
            $perfil->update(['unidade_id' => $unidade->id, 'curso_id' => $curso->id, 'default' => $curso->nome, 'situacao' => $request->situacao]);
        }

        $user = Auth::user();

        switch ($usuario->tipo) {
            case "aluno":
                break;
                case "bibliotecario":
                case "analistabibliotecario":
                $usuario->active = $request->input('active') ? 1 : 0;
                if($usuario->bibliotecario) {
                    $usuario->bibliotecario->crb = $request->crb;
                    if ($request->filled('matricula')) {
                        $usuario->bibliotecario->matricula = $request->matricula;
                    }
                    $usuario->bibliotecario->update();
                }

                $usuario->update();

                break;
            case "servidor":
                $usuario->active = $request->input('active') ? 1 : 0;
                $usuario->update();

                break;
        }

        if ($user->tipo == "administrador") {
            return redirect()->route('listar-usuario')->with('success', 'O usuário foi atualizado!');
        } else {
            return redirect()->route('listar_alunos')->with('success', 'O usuário foi atualizado!');
        }

    }

}
