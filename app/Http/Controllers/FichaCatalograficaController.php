<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFichaCatalograficaRequest;
use App\Http\Requests\UpdateFichaCatalograficaRequest;
use App\Models\Aluno;
use App\Models\Dissertacao;
use App\Models\FichaCatalografica;
use App\Models\Monografia;
use App\Models\PalavraChave;
use App\Models\Perfil;
use App\Models\ProgramaEducacional;
use App\Models\Tcc;
use App\Models\Tese;
use App\Models\TipoFichaCatalografica;
use App\Models\Unidade;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FichaCatalograficaController extends Controller
{
    private const SLUGS_DISPONIVEIS = [
        'dissertacao',
        'monografia',
        'produto-educacional',
        'tese',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fichas = Auth::user()
            ->aluno
            ->fichasCatalograficas()
            ->with('tipoFichaCatalografica')
            ->orderByDesc('updated_at')
            ->get();

        return view('fichas_catalograficas.index', compact('fichas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuario = Auth::user();
        $tipos_fichas_catalograficas = TipoFichaCatalografica::whereIn('slug', self::SLUGS_DISPONIVEIS)
            ->orderBy('id')
            ->get();

        $aluno = $usuario->aluno;
        $perfil = $this->resolverPerfilAluno($aluno);
        $curso = $perfil?->curso;

        return view('fichas_catalograficas.create', compact('usuario', 'aluno', 'curso', 'perfil', 'tipos_fichas_catalograficas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFichaCatalograficaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFichaCatalograficaRequest $request)
    {
        $idUser = Auth::user()->id;
        $aluno = Aluno::where('user_id', $idUser)->firstOrFail();
        $perfil = $this->resolverPerfilAluno($aluno);
        $unidade = $perfil ? Unidade::find($perfil->unidade_id) : null;

        $dados = $request->validated();
        $tipoFicha = TipoFichaCatalografica::findOrFail($dados['tipo_ficha_catalografica_id']);

        DB::transaction(function () use ($request, $dados, $aluno, $perfil, $unidade, $tipoFicha) {
            $dadosFicha = Arr::only($dados, [
                'autor_nome',
                'autor_sobrenome',
                'cutter',
                'area_conhecimento',
                'titulo',
                'subtitulo',
                'local',
                'ano',
                'folhas',
                'ilustracao',
                'inclui_anexo',
                'inclui_apendice',
                'nome_orientador',
                'sobrenome_orientador',
                'nome_coorientador',
                'sobrenome_coorientador',
                'nome_segundo_coorientador',
                'sobrenome_segundo_coorientador',
            ]);

            $dadosFicha['tipo_ficha_catalografica_id'] = $tipoFicha->id;
            $dadosFicha['aluno_id'] = $aluno->id;

            if ($request->hasFile('anexo')) {
                $arquivo = $request->file('anexo');

                $nomeAnexo = 'documento_'
                    . $aluno->cpf
                    . now()->format('YmdHis')
                    . '.'
                    . $arquivo->extension();

                $arquivo->storeAs('fichas', $nomeAnexo);

                $dadosFicha['anexo'] = $nomeAnexo;
            }

            $ficha = FichaCatalografica::create($dadosFicha);

            match ($tipoFicha->slug) {
                'dissertacao' => $ficha->dissertacao()->create([
                    'campus' => $unidade?->nome ?? '',
                    'programa' => $dados['programa'],
                ]),

                'monografia' => $ficha->monografia()->create([
                    'curso' => $perfil?->default ?? '',
                    'tipo_curso' => $dados['tipo_curso'],
                    'campus' => $unidade?->nome ?? '',
                ]),

                'produto-educacional', 'programa_educacional' => $ficha->programaEducacional()->create([
                    'programa' => $dados['programa'],
                    'campus' => $unidade?->nome ?? '',
                    'produto' => $dados['produto'],
                ]),

                'tese' => $ficha->tese()->create([
                    'programa' => $dados['programa'],
                ]),
            };

            $palavras = collect([
                $dados['primeira_chave'],
                $dados['segunda_chave'],
                $dados['terceira_chave'],
                $dados['quarta_chave'] ?? null,
                $dados['quinta_chave'] ?? null,
            ])
                ->filter(fn ($palavra) => $palavra !== null && trim($palavra) !== '')
                ->map(fn ($palavra) => ['palavra' => trim($palavra)])
                ->values()
                ->all();

            $ficha->palavrasChave()->createMany($palavras);

            return $ficha;
        });

        return redirect(route('fichas.index'))->with('success', 'Ficha catalográfica criada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FichaCatalografica  $fichaCatalografica
     * @return \Illuminate\Http\Response
     */
    public function show(FichaCatalografica $fichaCatalografica)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FichaCatalografica  $ficha
     * @return \Illuminate\Http\Response
     */
    public function edit(FichaCatalografica $ficha)
    {
        $ficha->load([
            'tipoFichaCatalografica',
            'dissertacao',
            'monografia',
            'tese',
            'programaEducacional',
            'palavrasChave',
            'aluno',
            'requisicaoDocumento.requisicao.perfil',
        ]);

        if (!$this->usuarioPodeEditarFicha($ficha)) {
            if (Auth::user()->tipo === 'aluno') {
                return redirect()->route('fichas.index')->with('fail', 'Você não tem permissão para editar esta ficha.');
            }
            abort(403);
        }

        $tipos_fichas_catalograficas = TipoFichaCatalografica::whereIn('slug', self::SLUGS_DISPONIVEIS)
            ->orderBy('id')
            ->get();

        $aluno = $this->resolverAlunoDaFicha($ficha);
        $perfil = $this->resolverPerfilDaFicha($ficha, $aluno);
        $curso = $perfil?->curso;

        return view('fichas_catalograficas.edit', compact('ficha', 'tipos_fichas_catalograficas', 'aluno', 'perfil', 'curso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFichaCatalograficaRequest  $request
     * @param  int  $ficha_catalografica_id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFichaCatalograficaRequest $request, $ficha_catalografica_id)
    {
        $user = Auth::user();

        $ficha = FichaCatalografica::with(['aluno', 'requisicaoDocumento.requisicao.perfil'])->findOrFail($ficha_catalografica_id);

        if (!$this->usuarioPodeEditarFicha($ficha)) {
            if ($user->tipo === 'aluno') {
                return back()->with('fail', 'Você não tem permissão para editar esta ficha.');
            }
            abort(403);
        }

        $aluno = $this->resolverAlunoDaFicha($ficha);
        $perfil = $this->resolverPerfilDaFicha($ficha, $aluno);
        $unidade = $perfil ? Unidade::find($perfil->unidade_id) : null;

        $dados = $request->validated();
        $tipoFicha = TipoFichaCatalografica::findOrFail($dados['tipo_ficha_catalografica_id']);

        DB::transaction(function () use ($request, $dados, $aluno, $perfil, $unidade, $ficha, $tipoFicha) {
            $dadosFicha = Arr::only($dados, [
                'autor_nome',
                'autor_sobrenome',
                'cutter',
                'area_conhecimento',
                'titulo',
                'subtitulo',
                'local',
                'ano',
                'folhas',
                'ilustracao',
                'inclui_anexo',
                'inclui_apendice',
                'nome_orientador',
                'sobrenome_orientador',
                'nome_coorientador',
                'sobrenome_coorientador',
                'nome_segundo_coorientador',
                'sobrenome_segundo_coorientador',
            ]);

            $dadosFicha['tipo_ficha_catalografica_id'] = $tipoFicha->id;
            if ($aluno) {
                $dadosFicha['aluno_id'] = $aluno->id;
            }

            if ($request->hasFile('anexo')) {
                $arquivo = $request->file('anexo');

                $nomeAnexo = 'documento_'
                    . ($aluno?->cpf ?? $ficha->id)
                    . now()->format('YmdHis')
                    . '.'
                    . $arquivo->extension();

                $arquivo->storeAs('fichas', $nomeAnexo);

                $dadosFicha['anexo'] = $nomeAnexo;
            }

            $ficha->update($dadosFicha);

            $slug = $tipoFicha->slug;

            if ($slug !== 'dissertacao') {
                $ficha->dissertacao()->delete();
            }

            if ($slug !== 'monografia') {
                $ficha->monografia()->delete();
            }

            if (!in_array($slug, ['produto-educacional', 'programa_educacional'], true)) {
                $ficha->programaEducacional()->delete();
            }

            if ($slug !== 'tese') {
                $ficha->tese()->delete();
            }

            match ($slug) {
                'dissertacao' => $ficha->dissertacao()->updateOrCreate(
                    [],
                    [
                        'campus' => $unidade?->nome ?? '',
                        'programa' => $dados['programa'],
                    ]
                ),

                'monografia' => $ficha->monografia()->updateOrCreate(
                    [],
                    [
                        'curso' => $perfil?->default ?? '',
                        'tipo_curso' => $dados['tipo_curso'],
                        'campus' => $unidade?->nome ?? '',
                    ]
                ),

                'produto-educacional', 'programa_educacional' => $ficha->programaEducacional()->updateOrCreate(
                    [],
                    [
                        'programa' => $dados['programa'],
                        'campus' => $unidade?->nome ?? '',
                        'produto' => $dados['produto'],
                    ]
                ),

                'tese' => $ficha->tese()->updateOrCreate(
                    [],
                    [
                        'programa' => $dados['programa'],
                    ]
                ),
            };

            $palavras = collect([
                $dados['primeira_chave'],
                $dados['segunda_chave'],
                $dados['terceira_chave'],
                $dados['quarta_chave'] ?? null,
                $dados['quinta_chave'] ?? null,
            ])
                ->filter(fn ($palavra) => $palavra !== null && trim($palavra) !== '')
                ->map(fn ($palavra) => ['palavra' => trim($palavra)])
                ->values()
                ->all();

            $ficha->palavrasChave()->delete();
            $ficha->palavrasChave()->createMany($palavras);
        });

        if ($user->tipo === 'bibliotecario') {
            return redirect()->route('listar-fichas')->with('success', 'Ficha atualizada com sucesso!');
        }

        return redirect()->route('fichas.index')->with('success', 'Ficha atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FichaCatalografica  $fichaCatalografica
     * @return \Illuminate\Http\Response
     */
    public function destroy(FichaCatalografica $fichaCatalografica)
    {
        //
    }

    public function gerarFicha($ficha_catalografica_id)
    {
        $ficha = FichaCatalografica::with([
            'tipoFichaCatalografica',
            'aluno',
            'requisicaoDocumento.requisicao.perfil.unidade',
        ])->findOrFail($ficha_catalografica_id);

        if (!$this->usuarioPodeBaixarFicha($ficha)) {
            abort(403, 'Você não tem permissão para baixar esta ficha.');
        }

        $palavras = PalavraChave::where('ficha_catalografica_id', $ficha->id)->orderBy('id')->get();
        $tipo_ficha = $ficha->tipoFichaCatalografica->slug;

        $aluno = $this->resolverAlunoDaFicha($ficha);
        $perfil = $this->resolverPerfilDaFicha($ficha, $aluno);
        $unidade = $perfil?->unidade ?? ($perfil ? Unidade::find($perfil->unidade_id) : null);

        if ($tipo_ficha === 'monografia') {
            $documento = Monografia::where('ficha_catalografica_id', $ficha->id)->first();
        } elseif ($tipo_ficha === 'tese') {
            $documento = Tese::where('ficha_catalografica_id', $ficha->id)->first();
        } elseif (strtolower($tipo_ficha) === 'tcc') {
            $documento = Tcc::where('ficha_catalografica_id', $ficha->id)->first();
        } elseif (in_array($tipo_ficha, ['produto-educacional', 'programa_educacional'], true)) {
            $documento = ProgramaEducacional::where('ficha_catalografica_id', $ficha->id)->first();
        } else {
            $documento = Dissertacao::where('ficha_catalografica_id', $ficha->id)->first();
        }

        $pdf = Pdf::loadView('telas_bibliotecario.gerar_ficha', compact('ficha', 'palavras', 'tipo_ficha', 'documento', 'unidade'));

        $baseName = Str::slug(strip_tags($ficha->titulo) . '_' . $ficha->autor_sobrenome, '_');
        $filename = ($baseName !== '' ? $baseName : 'ficha_catalografica') . '_' . strtotime('now') . '.pdf';

        return $pdf->download($filename);
    }

    private function usuarioPodeEditarFicha(FichaCatalografica $ficha): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if ($user->tipo === 'bibliotecario') {
            return true;
        }

        if ($user->tipo === 'aluno') {
            return $this->alunoEhDonoDaFicha($ficha, $user->id);
        }

        return false;
    }

    private function usuarioPodeBaixarFicha(FichaCatalografica $ficha): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if (in_array($user->tipo, ['bibliotecario', 'analistabibliotecario'], true)) {
            return true;
        }

        if ($user->tipo === 'aluno') {
            return $this->alunoEhDonoDaFicha($ficha, $user->id);
        }

        return false;
    }

    private function alunoEhDonoDaFicha(FichaCatalografica $ficha, int $userId): bool
    {
        $aluno = $this->resolverAlunoDaFicha($ficha);

        return $aluno !== null && (int) $aluno->user_id === $userId;
    }

    private function resolverAlunoDaFicha(FichaCatalografica $ficha): ?Aluno
    {
        return $ficha->aluno ?? $ficha->requisicaoDocumento?->aluno;
    }

    private function resolverPerfilAluno(?Aluno $aluno): ?Perfil
    {
        if (!$aluno) {
            return null;
        }

        return Perfil::where('aluno_id', $aluno->id)->where('valor', true)->first()
            ?? Perfil::where('aluno_id', $aluno->id)->first();
    }

    private function resolverPerfilDaFicha(FichaCatalografica $ficha, ?Aluno $aluno): ?Perfil
    {
        return $ficha->requisicaoDocumento?->requisicao?->perfil
            ?? $this->resolverPerfilAluno($aluno);
    }
}
