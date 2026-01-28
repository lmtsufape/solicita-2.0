<?php

namespace App\Http\Controllers;

use App\Mail\AlertaFichaMail;
use App\Models\Aluno;
use App\Models\Biblioteca;
use App\Models\Bibliotecario;
use App\Models\Dissertacao;
use App\Models\FichaCatalografica;
use App\Models\Monografia;
use App\Models\NadaConsta;
use App\Models\PalavraChave;
use App\Models\Perfil;
use App\Models\ProgramaEducacional;
use App\Models\Requisicao;
use App\Models\Requisicao_documento;
use App\Models\Tcc;
use App\Models\Tese;
use App\Models\TipoDocumento;
use App\Models\Unidade;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Mail\AlertaFichaGerada;
use App\Models\Deposito;
use App\Models\Retificacao;
use App\Notifications\AlertaDeposito;
use App\Notifications\AlertaNadaConsta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BibliotecarioController extends Controller
{
    // Redireciona para tela de login ao entrar no sistema
    public function index()
    {

        // return view('autenticacao.home-aluno');
        if (Auth::user()) {
            return redirect()->route('home');
        }
        return view('autenticacao.login');
    }

    public function listarSolicitacoes(Request $request)
    {
        $data_inicio = $request->data_inicio;
        $data_fim = $request->data_fim;

        $query = Requisicao_documento::leftJoin('depositos', 'requisicao_documentos.deposito_id', '=', 'depositos.id')
            ->leftJoin('ficha_catalograficas', 'requisicao_documentos.ficha_catalografica_id', '=', 'ficha_catalograficas.id')
            ->leftJoin('nada_constas', 'requisicao_documentos.nada_consta_id', '=', 'nada_constas.id')
            ->where(function($query) {
                $query->whereNotNull('requisicao_documentos.deposito_id')
                      ->orWhereNotNull('requisicao_documentos.ficha_catalografica_id')
                      ->orWhereNotNull('requisicao_documentos.nada_consta_id');
            });

        if ($data_inicio && $data_fim) {
            $query->whereBetween('requisicao_documentos.created_at', [
                Carbon::parse($data_inicio)->startOfDay(),
                Carbon::parse($data_fim)->endOfDay()
            ]);
        } elseif ($data_inicio) {
            $query->where('requisicao_documentos.created_at', '>=', Carbon::parse($data_inicio)->startOfDay());
        } elseif ($data_fim) {
            $query->where('requisicao_documentos.created_at', '<=', Carbon::parse($data_fim)->endOfDay());
        } else {
            $query->where('requisicao_documentos.created_at', '>=', Carbon::now()->subMonths(4));
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($sub_query) use ($search){
                $sub_query->where('depositos.autor_nome', 'ilike', "%{$search}%")
                ->orWhere('ficha_catalograficas.autor_nome', 'ilike', "%{$search}%")
                ->orWhere('nada_constas.autor_nome', 'ilike', "%{$search}%")
                ->orWhere('requisicao_documentos.id', 'ilike', "%{$search}%");
            });
        }

        $query->select(
            'requisicao_documentos.*',
            DB::raw('COALESCE(depositos.autor_nome, ficha_catalograficas.autor_nome, nada_constas.autor_nome) as autor_nome'),
            DB::raw('COALESCE(depositos.created_at, ficha_catalograficas.created_at, nada_constas.created_at) as entity_created_at'),
            DB::raw('COALESCE(depositos.updated_at, ficha_catalograficas.updated_at, nada_constas.updated_at) as entity_updated_at')
        );

        // Statistics for Dashboard
        $statsQuery = clone $query;
        $stats = [
            'total' => $statsQuery->count(),
            'concluidos' => (clone $statsQuery)->where('requisicao_documentos.status', 'Concluido')->count(),
            'em_andamento' => (clone $statsQuery)->where('requisicao_documentos.status', 'Em andamento')->count(),
            'rejeitados' => (clone $statsQuery)->where('requisicao_documentos.status', 'Rejeitado')->count(),
            'depositos' => (clone $statsQuery)->whereNotNull('requisicao_documentos.deposito_id')->count(),
            'nada_constas' => (clone $statsQuery)->whereNotNull('requisicao_documentos.nada_consta_id')->count(),
            'fichas' => (clone $statsQuery)->whereNotNull('requisicao_documentos.ficha_catalografica_id')->count(),
        ];

        $requisicoesFichas = $query->when($request->sort === 'status', function ($query) use ($request) {
            $query->orderByRaw("
                CASE requisicao_documentos.status
                    WHEN 'Em andamento' THEN 1
                    WHEN 'Concluido' THEN 2
                    WHEN 'Rejeitado' THEN 3
                    ELSE 4
                END " . ($request->direction ?? 'asc')
            );
        }, function ($query) use ($request) {
            $query->orderBy($request->sort ?? 'entity_created_at', $request->direction ?? 'desc');
        })->paginate(10);

        $idUser = Auth::user()->id;
        $bibliotecario = Bibliotecario::where('user_id', $idUser)->first();
        $unidadeBibliotecario = $bibliotecario->biblioteca->unidade_id;

        return view('telas_bibliotecario.listar_documentos_solicitados', compact('requisicoesFichas','idUser', 'bibliotecario', 'stats'));
    }

    public function visualizarFicha($requisicaoId)
    {
        $requisicao = Requisicao_documento::where('id', $requisicaoId)->first();
        $aluno = Aluno::where('id', $requisicao->aluno_id)->first();
        $palavrasChave = PalavraChave::where('ficha_catalografica_id', $requisicao->ficha_catalografica_id)->orderBy('id')->get();
        $fichaCatalografica = FichaCatalografica::where('id', $requisicao->ficha_catalografica_id)->first();
        $tipo_documento = $fichaCatalografica->tipo_documento_id;
        $documentoEspecificoNome = TipoDocumento::where('id', $tipo_documento)->first()->tipo;
        $bibliotecario = Bibliotecario::find($requisicao->bibliotecario_id);

        if ($documentoEspecificoNome == 'Monografia')
            $documento = Monografia::where('ficha_catalografica_id', $fichaCatalografica->id)->first();
        elseif ($documentoEspecificoNome == 'Tese')
            $documento = Tese::where('ficha_catalografica_id', $fichaCatalografica->id)->first();
        elseif ($documentoEspecificoNome == 'TCC')
            $documento = Tcc::where('ficha_catalografica_id', $fichaCatalografica->id)->first();
        elseif ($documentoEspecificoNome == 'ProgramaEduc')
            $documento = ProgramaEducacional::where('ficha_catalografica_id', $fichaCatalografica->id)->first();
        else
            $documento = Dissertacao::where('ficha_catalografica_id', $fichaCatalografica->id)->first();
        return view('telas_bibliotecario.visualizar_ficha', compact('documento', 'aluno', 'palavrasChave', 'fichaCatalografica', 'tipo_documento', 'requisicao', 'bibliotecario'));
    }

    public function editarFicha($requisicaoId)
    {
        $requisicao = Requisicao_documento::findOrFail( $requisicaoId);
        $aluno = Aluno::findOrFail( $requisicao->aluno_id);
        $palavrasChave = PalavraChave::where('ficha_catalografica_id', $requisicao->ficha_catalografica_id)->orderBy('id')->get();
        $fichaCatalografica = FichaCatalografica::findOrFail($requisicao->ficha_catalografica_id);
        $tipo_documento = $fichaCatalografica->tipo_documento_id;
        $documentoEspecificoNome = TipoDocumento::where('id', $tipo_documento)->first()->tipo;
        $bibliotecario = Bibliotecario::find($requisicao->bibliotecario_id);
        $bibli = Bibliotecario::where('user_id', Auth::user()->id)->first();

        $data_bibi = date_create_from_format('Y-m-d H:i:s', $requisicao->updated_at);
        $data_agora = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        if ($requisicao->bibliotecario_id == null || (date_diff($data_bibi, $data_agora)->h >= 2 && $requisicao->status == 'Em andamento')) {
            $requisicao->bibliotecario_id = $bibli->id;
            $requisicao->save();
        }
        if ($bibliotecario != null && $requisicao->status == 'Em andamento' && $bibliotecario->id != $bibli->id) {
            $requisicao->bibliotecario_id = $bibli->id;
            $requisicao->update();
        }
        if ($bibliotecario != null && ($requisicao->status == 'Concluido' || $requisicao->status == 'Rejeitado')) {
            return redirect(route('listar-fichas'))->with('error', 'Esta requisição foi concluida ou rejeitada pelo bibliotecario: ' . $bibliotecario->user->name);
        }
        if ($documentoEspecificoNome == 'Monografia')
            $documento = Monografia::where('ficha_catalografica_id', $fichaCatalografica->id)->first();
        elseif ($documentoEspecificoNome == 'Tese')
            $documento = Tese::where('ficha_catalografica_id', $fichaCatalografica->id)->first();
        elseif ($documentoEspecificoNome == 'TCC')
            $documento = Tcc::where('ficha_catalografica_id', $fichaCatalografica->id)->first();
        elseif ($documentoEspecificoNome == 'ProgramaEduc')
            $documento = ProgramaEducacional::where('ficha_catalografica_id', $fichaCatalografica->id)->first();
        else
            $documento = Dissertacao::where('ficha_catalografica_id', $fichaCatalografica->id)->first();
        return view('telas_bibliotecario.editar_ficha', compact('documento', 'aluno', 'palavrasChave', 'fichaCatalografica', 'tipo_documento', 'requisicao', 'bibliotecario'));
    }

    public function avaliarNadaConsta($requisicaoId){

        $requisicao_documento = Requisicao_documento::find($requisicaoId);
        $requisicao = $requisicao_documento->requisicao;
        $aluno = $requisicao->perfil->aluno;
        $nadaConsta = $requisicao_documento->nadaConsta;
        $bibliotecario = $requisicao_documento->bibliotecario;
        $bibli = Bibliotecario::where('user_id', Auth::user()->id)->first();

        $data_bibi = date_create_from_format('Y-m-d H:i:s', $requisicao->updated_at);
        $data_agora = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        if ($requisicao_documento->bibliotecario_id == null || (date_diff($data_bibi, $data_agora)->h >= 2 && $requisicao_documento->status == 'Em andamento')) {
            $requisicao_documento->bibliotecario_id = $bibli->id;
            $requisicao_documento->save();
        }
        if ($bibliotecario != null && ($requisicao->status == 'Concluido' || $requisicao_documento->status == 'Rejeitado')) {
            return redirect(route('listar-fichas'))->with('error', 'Esta requisição foi concluida ou rejeitada pelo bibliotecario: ' . $bibliotecario->user->name);
        } elseif ($bibliotecario != null && $requisicao->status == 'Em andamento' && $bibliotecario->id != $bibli->id) {
            return redirect(route('listar-fichas'))->with('error', 'Esta requisição está sendo analisada pelo bibliotecario: ' . $bibliotecario->user->name);
        }


        return view('telas_bibliotecario.avaliar_nada_consta', compact('nadaConsta', 'aluno', 'requisicao', 'requisicao_documento', 'bibliotecario'));
    }

    public function editarNomeNadaConsta($requisicaoId){
        //view para editar apenas o nome

        $requisicao_documento = Requisicao_documento::find($requisicaoId);
        $requisicao = $requisicao_documento->requisicao;
        $aluno = $requisicao->perfil->aluno;
        $nadaConsta = $requisicao_documento->nadaConsta;
        $bibliotecario = $requisicao_documento->bibliotecario;
        $bibli = Bibliotecario::where('user_id', Auth::user()->id)->first();

        $data_bibi = date_create_from_format('Y-m-d H:i:s', $requisicao->updated_at);
        $data_agora = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        if ($requisicao_documento->bibliotecario_id == null || (date_diff($data_bibi, $data_agora)->h >= 2 && $requisicao_documento->status == 'Em andamento')) {
            $requisicao_documento->bibliotecario_id = $bibli->id;
            $requisicao_documento->save();
        }
        if ($bibliotecario != null && ($requisicao->status == 'Concluido' || $requisicao_documento->status == 'Rejeitado')) {
            return redirect(route('listar-fichas'))->with('error', 'Esta requisição foi concluida ou rejeitada pelo bibliotecario: ' . $bibliotecario->user->name);
        } elseif ($bibliotecario != null && $requisicao->status == 'Em andamento' && $bibliotecario->id != $bibli->id) {
            return redirect(route('listar-fichas'))->with('error', 'Esta requisição está sendo analisada pelo bibliotecario: ' . $bibliotecario->user->name);
        }

        return view('telas_bibliotecario.editar_nada_consta',compact('nadaConsta', 'aluno', 'requisicao', 'requisicao_documento', 'bibliotecario'));
    }

    public function avaliarDeposito($requisicaoId){

        $requisicao_documento = Requisicao_documento::find($requisicaoId);
        $requisicao = $requisicao_documento->requisicao;
        $aluno = $requisicao->perfil->aluno;
        $deposito = $requisicao_documento->deposito;
        $bibliotecario = $requisicao_documento->bibliotecario;
        $bibli = Bibliotecario::where('user_id', Auth::user()->id)->first();

        $data_bibi = date_create_from_format('Y-m-d H:i:s', $requisicao->updated_at);
        $data_agora = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        if ($requisicao_documento->bibliotecario_id == null || (date_diff($data_bibi, $data_agora)->h >= 2 && $requisicao_documento->status == 'Em andamento')) {
            $requisicao_documento->bibliotecario_id = $bibli->id;
            $requisicao_documento->save();
        }
        if ($bibliotecario != null && ($requisicao->status == 'Concluido' || $requisicao_documento->status == 'Rejeitado')) {
            return redirect(route('listar-fichas'))->with('error', 'Esta requisição foi concluida ou rejeitada pelo bibliotecario: ' . $bibliotecario->user->name);
        } elseif ($bibliotecario != null && $requisicao->status == 'Em andamento' && $bibliotecario->id != $bibli->id) {
            return redirect(route('listar-fichas'))->with('error', 'Esta requisição está sendo analisada pelo bibliotecario: ' . $bibliotecario->user->name);
        }


        return view('telas_bibliotecario.avaliar_deposito', compact('deposito', 'aluno', 'requisicao', 'requisicao_documento', 'bibliotecario'));
    }

    public function visualizarDeposito($requisicaoId)
    {
        $requisicao_documento = Requisicao_documento::where('id', $requisicaoId)->first();
        $requisicao = $requisicao_documento->requisicao;
        $aluno = $requisicao_documento->aluno;
        $deposito = $requisicao_documento->deposito;
        $bibliotecario = $requisicao_documento->bibliotecario;

        return view('telas_bibliotecario.visualizar_deposito', compact( 'aluno', 'deposito', 'requisicao_documento', 'requisicao', 'bibliotecario'));
    }

    public function baixarAnexoTccDeposito(Requisicao_documento $requisicao)
    {
        if ($requisicao->deposito_id != null && $requisicao->deposito->anexo_tcc && Storage::exists($requisicao->deposito->anexo_tcc)) {
            return Storage::download($requisicao->deposito->anexo_tcc);
        }
        abort(404);
    }

    public function baixarAnexoAutorizacaoDeposito(Requisicao_documento $requisicao)
    {
        if ($requisicao->deposito_id != null && $requisicao->deposito->anexo_comprovante_autorizacao && Storage::exists($requisicao->deposito->anexo_comprovante_autorizacao)) {
            return Storage::download($requisicao->deposito->anexo_comprovante_autorizacao);
        }
        abort(404);
    }

    public function baixarAnexoPublicacaoParcial(Requisicao_documento $requisicao)
    {
        if ($requisicao->deposito_id != null && $requisicao->deposito->anexo_publicacao_parcial && Storage::exists($requisicao->deposito->anexo_publicacao_parcial)) {
            return Storage::download($requisicao->deposito->anexo_publicacao_parcial);
        }
        abort(404);
    }

    public function baixarAnexoRetificacao(Retificacao $retificacao)
    {
        if ($retificacao->anexo && Storage::exists($retificacao->anexo)) {
            return Storage::download($retificacao->anexo);
        }
        abort(404);
    }

    public function baixarAnexoComprovanteDeposito(Requisicao_documento $requisicao)
    {
        if ($requisicao->deposito_id != null && $requisicao->deposito->anexo_comprovante_deposito && Storage::exists($requisicao->deposito->anexo_comprovante_deposito)) {
            return Storage::download($requisicao->deposito->anexo_comprovante_deposito);
        }
        abort(404);
    }

    public function deferirDeposito(Request $request)
    {
        $documentoRequisitado = Requisicao_documento::find($request->requisicao_documento_id);
        $status = 'Concluido';
        $this->atualizarStatusRequisicaoDeposito($documentoRequisitado, $status);
        $deposito = $documentoRequisitado->deposito;
        $deposito->anexo_comprovante_deposito = $request->file('comprovante')->store('deposito');
        $deposito->save();
        return redirect(route('listar-fichas'))->with('success', 'Solicitação atualizada com sucesso!');
    }

    public function indeferirDeposito(Request $request)
    {
        $documentoRequisitado = Requisicao_documento::find($request->requisicao_documento_id);
        $status = 'Rejeitado';
        $documentoRequisitado->anotacoes = $request->justificativa;
        $this->atualizarStatusRequisicaoDeposito($documentoRequisitado, $status);
        return redirect(route('listar-fichas'))->with('success', 'Solicitação atualizada com sucesso!');
    }

    public function gerarDeposito(Requisicao_documento $requisicao_documento)
    {
        $requisicao = $requisicao_documento->requisicao;
        $discente = $requisicao_documento->deposito->autor_nome ?? $requisicao->aluno->user->name;
        $curso = $requisicao->perfil->curso->nome;
        $cpf = $requisicao->aluno->cpf;
        $bibliotecario = $requisicao_documento->bibliotecario;
        $tcc =  $requisicao_documento->deposito->titulo_tcc;
        $registro_patente = $requisicao_documento->deposito->registro_patente ?? 'Não';
        $pdf = Pdf::loadView('telas_bibliotecario.gerar_deposito', compact('discente', 'cpf', 'curso', 'bibliotecario', 'tcc', 'registro_patente'));
        $filename = 'deposito_' . preg_replace("/[^A-Za-z]+/", "", $discente) .'.pdf';
        return $pdf->download($filename);
    }

    private function atualizarStatusRequisicaoDeposito($documentoRequisitado, $status)
    {
        $loggedUserId = Auth::user()->id;
        $bibliotecario = Bibliotecario::where('user_id',$loggedUserId)->first();
        $documentoRequisitado->bibliotecario_id = $bibliotecario->id;
        $documentoRequisitado->status = $status;
        $documentoRequisitado->save();
        $alunoUser = $documentoRequisitado->aluno->user;
        $alunoUser->notify(new AlertaDeposito($documentoRequisitado->status, $documentoRequisitado->anotacoes));
    }

    public function retificarRequisicaoDocumento(Request $request)
    {
        $requisicao_documento = Requisicao_documento::find($request->requisicao_documento_id);
        $retificacao = new Retificacao();
        if ($request->hasFile('anexo')) {
            $retificacao->anexo = Storage::put('retificacoes/', $request->file('anexo'));
        }
        $requisicao_documento->retificacoes()->save($retificacao);
        $justificativa = 'A sua solicitação foi retificada, por favor acesse o sistema para baixar a versão atualizada.';
        if ($requisicao_documento->nadaConsta()->exists()) {
            $requisicao_documento->requisicao->aluno->user->notify(new AlertaNadaConsta('retificado', $justificativa));
        } elseif ($requisicao_documento->deposito()->exists()) {
            $requisicao_documento->requisicao->aluno->user->notify(new AlertaDeposito('retificado', $justificativa));
        }
        return redirect()->back()->with('success', 'Solicitação retificada com sucesso!');
    }

    public function visualizarNadaConsta($requisicaoId)
    {
        $requisicao_documento = Requisicao_documento::where('id', $requisicaoId)->first();
        $requisicao = $requisicao_documento->requisicao;
        $aluno = $requisicao_documento->aluno;
        $nadaConsta = $requisicao_documento->nadaConsta;
        $bibliotecario = $requisicao_documento->bibliotecario;

        return view('telas_bibliotecario.visualizar_nada_consta', compact( 'aluno', 'nadaConsta', 'requisicao_documento', 'requisicao', 'bibliotecario'));
    }

    public function gerarNadaConsta(Requisicao_documento $requisicao_documento)
    {
        $requisicao = $requisicao_documento->requisicao;
        $discente = $requisicao_documento->nadaConsta->autor_nome ?? $requisicao->aluno->user->name;
        $curso = $requisicao->perfil->curso->nome;
        $cpf = $requisicao->aluno->cpf;
        $bibliotecario = $requisicao_documento->bibliotecario;
        $pdf = Pdf::loadView('telas_bibliotecario.gerar_nada_consta', compact('discente', 'cpf', 'curso', 'bibliotecario'));
        $filename = 'nada_consta_' . preg_replace("/[^A-Za-z]+/", "", $discente) .'.pdf';
        return $pdf->download($filename);
    }

    public function atualizarFicha(Request $request)
    {
        $ficha = FichaCatalografica::find($request->ficha_catalografica_id);
        $ficha->autor_nome = $request->autor_nome;
        $ficha->autor_sobrenome = $request->autor_sobrenome;
        if ($ficha->cutter == null)
            $ficha->cutter = $request->cutter;
        if ($ficha->classificacao == null)
            $ficha->classificacao = $request->classificacao;
        $ficha->titulo = $request->titulo;
        $ficha->subtitulo = $request->subtitulo;
        $ficha->local = $request->local;
        $ficha->ano = $request->ano;
        $ficha->folhas = $request->folhas;
        $ficha->ilustracao = $request->ilustracao;
        $ficha->inclui_anexo = $request->inclui_anexo;
        $ficha->inclui_apendice = $request->inclui_apendice;
        $ficha->update();


        if ($request->tipo_documento == 2) {
            $monografia = Monografia::where('ficha_catalografica_id', $request->ficha_catalografica_id)->first();
            $monografia->nome_orientador = $request->nome_orientador;
            $monografia->sobrenome_orientador = $request->sobrenome_orientador;
            $monografia->nome_coorientador = $request->nome_coorientador;
            $monografia->sobrenome_coorientador = $request->sobrenome_coorientador;
            $monografia->curso = $request->curso;
            $monografia->campus = $request->campus;
            $monografia->tipo_curso = $request->tipo_curso;
            $monografia->update();
        } elseif ($request->tipo_documento == 4) {
            $tese = Tese::where('ficha_catalografica_id', $request->ficha_catalografica_id)->first();
            $tese->nome_orientador = $request->nome_orientador;
            $tese->nome_coorientador = $request->nome_coorientador;
            $tese->sobrenome_orientador = $request->sobrenome_orientador;
            $tese->sobrenome_coorientador = $request->sobrenome_coorientador;
            $tese->programa = $request->programa;
            $tese->update();
        } elseif ($request->tipo_documento == 3) {
            $programaEduc = ProgramaEducacional::where('ficha_catalografica_id', $request->ficha_catalografica_id)->first();
            $programaEduc->programa = $request->programa;
            $programaEduc->campus = $request->campus;
            $programaEduc->nome_orientador = $request->nome_orientador;
            $programaEduc->sobrenome_orientador = $request->sobrenome_orientador;
            $programaEduc->nome_coorientador = $request->nome_coorientador;
            $programaEduc->sobrenome_coorientador = $request->sobrenome_coorientador;
            $programaEduc->produto = $request->produto;
            $programaEduc->update();
        } elseif ($request->tipo_documento == 1) {
            $dissertacao = Dissertacao::where('ficha_catalografica_id', $request->ficha_catalografica_id)->first();
            $dissertacao->nome_orientador = $request->nome_orientador;
            $dissertacao->nome_coorientador = $request->nome_coorientador;
            $dissertacao->sobrenome_orientador = $request->sobrenome_orientador;
            $dissertacao->sobrenome_coorientador = $request->sobrenome_coorientador;
            $dissertacao->campus = $request->campus;
            $dissertacao->programa = $request->programa;
            $dissertacao->update();
        } else {
            $request;
        }

        $palavra = PalavraChave::find( $request->palavra_chave1_id);
        $palavra->palavra = $request->primeira_chave;
        $palavra->update();

        $palavra = PalavraChave::find( $request->palavra_chave2_id);
        $palavra->palavra = $request->segunda_chave;
        $palavra->update();

        $palavra = PalavraChave::find( $request->palavra_chave3_id);
        $palavra->palavra = $request->terceira_chave;
        $palavra->update();

        if ($request->quarta_chave != null) {
            $palavra = PalavraChave::find( $request->palavra_chave4_id);
            $palavra->palavra = $request->quarta_chave;
            $palavra->update();
        }else{
            $palavra = PalavraChave::find( $request->palavra_chave4_id);
            if($palavra){
                $palavra->delete();
            }
        }

        if ($request->quinta_chave != null) {
            $palavra = PalavraChave::where('id', $request->palavra_chave5_id)->first();
            $palavra->palavra = $request->quinta_chave;
            $palavra->update();
        }else{
            $palavra = PalavraChave::where('id', $request->palavra_chave5_id)->first();
            if($palavra){
                $palavra->delete();
            }
        }

        $userId = Auth::user()->id;
        $bibliotecario = Bibliotecario::where('user_id', $userId)->first();


        $documentosRequisitados = Requisicao_documento::where('ficha_catalografica_id', $request->ficha_catalografica_id)->first();
        $documentosRequisitados->status = 'Concluido';
        $documentosRequisitados->updated_at = time();
        $documentosRequisitados->bibliotecario_id = $bibliotecario->id;
        $documentosRequisitados->update();
        $alunoUser = User::find(Aluno::find($documentosRequisitados->aluno_id)->user_id);

        \Illuminate\Support\Facades\Mail::send(new AlertaFichaGerada($alunoUser, $documentosRequisitados));
        return redirect(Route('listar-fichas'))->with('success', 'Ficha Atualizada com Sucesso!');
    }

    public function rejeitarFicha($requisicaoId)
    {
        $requisicao = Requisicao_documento::find($requisicaoId);
        $ficha = FichaCatalografica::find($requisicao->ficha_catalografica_id);
        $aluno = Aluno::find($requisicao->aluno_id);
        $usuario = User::find($aluno->user_id);

        return view('telas_bibliotecario.rejeitar_ficha', compact('ficha', 'usuario', 'requisicao'));
    }

    public function atualizarRejeicao($requisicaoId, Request $request)
    {


        $requisicao = Requisicao_documento::find($requisicaoId);
        $requisicao->anotacoes = $request->mensagem;
        $requisicao->status = 'Rejeitado';
        $requisicao->updated_at = time();
        $idUser = Auth::user()->id;
        $bibliotecario = Bibliotecario::where('user_id', $idUser)->first();
        $requisicao->bibliotecario_id = $bibliotecario->id;

        $requisicao->update();

        $alunoUser = User::find(Aluno::find($requisicao->aluno_id)->user_id);

        \Illuminate\Support\Facades\Mail::send(new AlertaFichaGerada($alunoUser, $requisicao));
        return redirect(Route('listar-fichas'));
    }

    public function previewFicha(Request $request, $requisicao_id)
    {
        $requisicao = Requisicao_documento::find($requisicao_id);
        $bibliotecario = Bibliotecario::find($requisicao->bibliotecario_id);
        $unidade = Unidade::find($bibliotecario->biblioteca->unidade_id);
        $perfil = Perfil::where('aluno_id', $request->aluno_id)->first();

        $ficha = new FichaCatalografica();

        $ficha->autor_nome = $request->autor_nome;
        $ficha->autor_sobrenome = $request->autor_sobrenome;
        $ficha->titulo = $request->titulo;
        $ficha->subtitulo = $request->subtitulo;
        $ficha->local = $request->local;
        $ficha->ano = $request->ano;
        $ficha->folhas = $request->folhas;
        $ficha->cutter = $request->cutter;
        $ficha->classificacao = $request->classificacao;
        $ficha->ilustracao = $request->ilustracao;
        $ficha->tipo_documento_id = $request->tipo_documento;
        $ficha->inclui_anexo = $request->inclui_anexo;
        $ficha->inclui_apendice = $request->inclui_apendice;

        $documento = null;

        if ($request->tipo_documento == 2) {
            $original = FichaCatalografica::find($request->ficha_catalografica_id);
            $perfil = $original->requisicaoDocumento->requisicao->perfil;
            $documento = new Monografia();
            $documento->nome_orientador = $request->nome_orientador;
            $documento->sobrenome_orientador = $request->sobrenome_orientador;
            $documento->nome_coorientador = $request->nome_coorientador;
            $documento->sobrenome_coorientador = $request->sobrenome_coorientador;
            $documento->nome_segundo_coorientador = $request->nome_segundo_coorientador;
            $documento->sobrenome_segundo_coorientador = $request->sobrenome_segundo_coorientador;
            $documento->curso = $perfil->default;
            $documento->tipo_curso = $request->tipo_curso;
            $documento->campus = $unidade->nome;
            $documento->ficha_catalografica_id = $ficha->id;
        } elseif ($request->tipo_documento == 4) {
            $documento = new Tese();
            $documento->nome_orientador = $request->nome_orientador;
            $documento->sobrenome_orientador = $request->sobrenome_orientador;
            $documento->nome_coorientador = $request->nome_coorientador;
            $documento->sobrenome_coorientador = $request->sobrenome_coorientador;
            $documento->programa = $request->programa;
            $documento->ficha_catalografica_id = $ficha->id;
        } elseif ($request->tipo_documento == 3) {
            $documento = new ProgramaEducacional();
            $documento->programa = $request->programa;
            $documento->campus = $unidade->nome;
            $documento->ficha_catalografica_id = $ficha->id;
            $documento->nome_orientador = $request->nome_orientador;
            $documento->sobrenome_orientador = $request->sobrenome_orientador;
            $documento->nome_coorientador = $request->nome_coorientador;
            $documento->sobrenome_coorientador = $request->sobrenome_coorientador;
            $documento->produto = $request->produto;
        } elseif ($request->tipo_documento == 1) {
            $documento = new Dissertacao();
            $documento->nome_orientador = $request->nome_orientador;
            $documento->sobrenome_orientador = $request->sobrenome_orientador;
            $documento->nome_coorientador = $request->nome_coorientador;
            $documento->sobrenome_coorientador = $request->sobrenome_coorientador;
            $documento->nome_segundo_coorientador = $request->nome_segundo_coorientador;
            $documento->sobrenome_segundo_coorientador = $request->sobrenome_segundo_coorientador;
            $documento->campus = $unidade->nome;
            $documento->programa = $request->programa;
            $documento->ficha_catalografica_id = $ficha->id;
        }
        $palavras = array();
        $palavra = new PalavraChave();
        $palavra->palavra = $request->primeira_chave;
        $palavra->ficha_catalografica_id = $ficha->id;
        array_push($palavras, $palavra);

        $palavra = new PalavraChave();
        $palavra->palavra = $request->segunda_chave;
        $palavra->ficha_catalografica_id = $ficha->id;
        array_push($palavras, $palavra);

        $palavra = new PalavraChave();
        $palavra->palavra = $request->terceira_chave;
        $palavra->ficha_catalografica_id = $ficha->id;
        array_push($palavras, $palavra);

        if ($request->quarta_chave != null) {
            $palavra = new PalavraChave();
            $palavra->palavra = $request->quarta_chave;
            $palavra->ficha_catalografica_id = $ficha->id;
            array_push($palavras, $palavra);
        }
        if ($request->quinta_chave != null) {
            $palavra = new PalavraChave();
            $palavra->palavra = $request->quinta_chave;
            $palavra->ficha_catalografica_id = $ficha->id;
            array_push($palavras, $palavra);
        }

        $requisicao = new Requisicao();
        $requisicao->data_pedido = date('Y-m-d');
        $requisicao->hora_pedido = date('H:i');
        $requisicao->perfil_id = $perfil->id;
        $requisicao->aluno_id = 1; //necessária adequação com o código de autenticação do usuário do perfil aluno

        $requisicao = new Requisicao_documento();
        $requisicao->status_data = date('Y-m-d');
        $requisicao->requisicao_id = $requisicao->id;
        $requisicao->aluno_id = $perfil->aluno_id;
        $requisicao->status = 'Em andamento';
        $requisicao->ficha_catalografica_id = $ficha->id;

        $tipo_documento = TipoDocumento::find($ficha->tipo_documento_id)->tipo;
        $userBibliotecario = User::find($bibliotecario->user_id);

        $pdf = Pdf::loadView('telas_bibliotecario.gerar_ficha', compact('ficha', 'palavras', 'tipo_documento', 'documento', 'bibliotecario', 'unidade', 'userBibliotecario'));
        return $pdf->download($ficha->titulo . "_" . $ficha->autor . strtotime('now') . ".pdf");

    }

    public function gerarFicha($requisicaoId)
    {
        $requisicao = Requisicao_documento::find($requisicaoId);
        if ($requisicao->status != 'Concluido') {
            return redirect('home')->with('error', 'Ficha não concluida.');
        }
        $ficha = FichaCatalografica::find($requisicao->ficha_catalografica_id);
        $palavras = PalavraChave::where('ficha_catalografica_id', $ficha->id)->orderBy('id')->get();
        $tipo_documento = TipoDocumento::find($ficha->tipo_documento_id)->tipo;
        $bibliotecario = Bibliotecario::find($requisicao->bibliotecario_id);
        $userBibliotecario = User::find($bibliotecario->user_id);
        $biblioteca = Biblioteca::find($bibliotecario->biblioteca_id);
        $unidade = Unidade::find($biblioteca->unidade_id);

        if ($tipo_documento == 'Monografia')
            $documento = Monografia::where('ficha_catalografica_id', $ficha->id)->first();
        elseif ($tipo_documento == 'Tese')
            $documento = Tese::where('ficha_catalografica_id', $ficha->id)->first();
        elseif ($tipo_documento == 'TCC')
            $documento = Tcc::where('ficha_catalografica_id', $ficha->id)->first();
        elseif ($tipo_documento == 'ProgramaEduc')
            $documento = ProgramaEducacional::where('ficha_catalografica_id', $ficha->id)->first();
        else
            $documento = Dissertacao::where('ficha_catalografica_id', $ficha->id)->first();

        $pdf = Pdf::loadView('telas_bibliotecario.gerar_ficha', compact('ficha', 'palavras', 'tipo_documento', 'documento', 'bibliotecario', 'unidade', 'userBibliotecario'));
        return $pdf->download($ficha->titulo . "_" . $ficha->autor . strtotime('now') . ".pdf");
    }

    public function baixarAnexo($requisicaoId)
    {

        $requsicao = Requisicao_documento::find($requisicaoId);
        $ficha = FichaCatalografica::find($requsicao->ficha_catalografica_id);
        return Storage::download('fichas/' . $ficha->anexo);
    }

    public function deferirNadaConsta(Request $request)
    {
        $documentoRequisitado = Requisicao_documento::find($request->requisicao_documento_id);
        $status = 'Concluido';
        $this->atualizarStatusRequisicaoNadaConsta($documentoRequisitado, $status);
        $nadaConsta = $documentoRequisitado->nadaConsta;
        $nadaConsta->anexo_comprovante_deposito = $request->file('comprovante')->store('nada_consta');
        $nadaConsta->save();
        return redirect(route('listar-fichas'))->with('success', 'Solicitação atualizada com sucesso!');
    }

    public function indeferirNadaConsta(Request $request)
    {
        $documentoRequisitado = Requisicao_documento::find($request->requisicao_documento_id);
        $status = 'Rejeitado';
        $documentoRequisitado->anotacoes = $request->justificativa;
        $this->atualizarStatusRequisicaoNadaConsta($documentoRequisitado, $status);
        return redirect(route('listar-fichas'))->with('success', 'Solicitação atualizada com sucesso!');
    }

    private function atualizarStatusRequisicaoNadaConsta($documentoRequisitado, $status)
    {
        $loggedUserId = Auth::user()->id;
        $bibliotecario = Bibliotecario::where('user_id',$loggedUserId)->first();
        $documentoRequisitado->bibliotecario_id = $bibliotecario->id;
        $documentoRequisitado->status = $status;
        $documentoRequisitado->save();
        $alunoUser = $documentoRequisitado->aluno->user;
        $alunoUser->notify(new AlertaNadaConsta($documentoRequisitado->status, $documentoRequisitado->anotacoes));
    }

    public function baixarNadaConsta(Requisicao_documento $requisicao_documento)
    {
        if ($requisicao_documento->nada_consta_id != null && $requisicao_documento->nadaConsta->anexo_comprovante_deposito && Storage::exists($requisicao_documento->nadaConsta->anexo_comprovante_deposito)) {
            return Storage::download($requisicao_documento->nadaConsta->anexo_comprovante_deposito);
        }
        abort(404);
    }

    public function baixarDeposito(Requisicao_documento $requisicao_documento)
    {
        if ($requisicao_documento->deposito_id != null && $requisicao_documento->deposito->anexo_comprovante_deposito && Storage::exists($requisicao_documento->deposito->anexo_comprovante_deposito)) {
            return Storage::download($requisicao_documento->deposito->anexo_comprovante_deposito);
        }
        abort(404);
    }

    public function baixarRetificacao(Retificacao $retificacao)
    {
        if ($retificacao->anexo && Storage::exists($retificacao->anexo)) {
            return Storage::download($retificacao->anexo);
        }
        abort(404);
    }


    public function baixarAnexoComprovante($requisicaoId)
    {

        $requsicao = Requisicao_documento::find($requisicaoId);
        $nadaConsta = NadaConsta::find($requsicao->nada_consta_id);
        return Storage::download($nadaConsta->anexo_comprovante_deposito);


    }

    public function baixarAnexoTermoAceitacao($requisicaoId)
    {

        $requsicao = Requisicao_documento::find($requisicaoId);
        $nadaConsta = NadaConsta::find($requsicao->nada_consta_id);
        return Storage::download('nadaConsta/' . $nadaConsta->anexo_termo_aceitacao);
    }

    public function createBibliotecario()
    {
        $bibliotecas = Biblioteca::All();
        return view('autenticacao.cadastro-bibliotecario', compact('bibliotecas'));
    }

    public function perfil()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser); //Usuário Autenticado
        $bibliotecario = Bibliotecario::where('user_id', $idUser)->first(); //Bibliotecario autenticado
        $biblioteca = Biblioteca::where('id', $bibliotecario->biblioteca_id)->first();
        return view('telas_bibliotecario.perfil_bibliotecario', ['user' => $user,
            'bibliotecario' => $bibliotecario, 'biblioteca' => $biblioteca]);

    }


    public function storeBibliotecario(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'matricula' => 'required|unique:bibliotecarios|numeric|digits_between:1,10',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'biblioteca' => 'required',
        ]);
        $usuario = new User();
        $usuario->name = $request->input('name');
        $usuario->email = $request->input('email');
        $usuario->password = Hash::make($request->input('password'));
        $usuario->tipo = 'bibliotecario';
        $usuario->save();

        // biblioteca
        $biblioteca = Biblioteca::where('id', $request->bibliotecas)->first();
        //INSTANCIA DO BIBLIOTECARIO
        $bibliotecario = new Bibliotecario();
        $bibliotecario->matricula = $request->input('matricula');
        $bibliotecario->user_id = $usuario->id;
        $bibliotecario->biblioteca_id = $request->input('biblioteca');
        $bibliotecario->crb = $request->crb;
        $bibliotecario->save();
        $usuario->sendEmailVerificationNotification();
        return redirect()->route('home')->with('success', 'Bibliotecario cadastrado com sucesso!');
    }

    public function editarBibliotecario()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser); //Usuário Autenticado
        $bibliotecario = Bibliotecario::where('user_id', $idUser)->first(); //Bibliotecario autenticado
        return view('telas_bibliotecario.editar_bibliotecario', ['user' => $user,
            'bibliotecario' => $bibliotecario]);
    }

    public function atualizarBibliotecario(Request $request)
    {
        //atualização dos dados

        $user = Auth::user();
        $bibliotecario = Bibliotecario::where('user_id', $user->id)->first();
        if ($user->email != $request->email) {
            $request->validate([
                'email' => ['bail', 'required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $bibliotecario->crb = $request->crb;

        $user->save();
        $bibliotecario->save();

        //dados para ser exibido na view
        $idUser = Auth::user()->id;
        $user = User::find($idUser); //Usuário Autenticado
        return redirect()->route('home-bibliotecario')
            ->with('success', 'Seus dados foram atualizados!');
    }

    public function editarSenha()
    {
        return view('telas_bibliotecario.alterar_senha_bibliotecario');
    }

    public function atualizarSenha(Request $request)
    {
        if (!Hash::check($request->atual, Auth::user()->password)) {
            return redirect()->back()->with('error', 'Senha atual está incorreta');
        }
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            // 'atual' => 'required|string|min:8',
        ]);
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('perfil-bibliotecario')
            ->with('success', 'Senha alterada com sucesso!');

    }


}


