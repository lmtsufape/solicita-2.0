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
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FichaCatalograficaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fichas = Auth::user()->aluno->fichasCatalograficas()->get();

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
        $tipos_fichas_catalograficas = TipoFichaCatalografica::whereIn('slug', ['dissertacao', 'monografia'])->get();

        $aluno = $usuario->aluno;
        $perfil = $usuario->aluno->perfil()->where('valor', true)->first();
        $curso = $perfil->curso;

        return view('fichas_catalograficas.create', compact('usuario', 'aluno', 'curso', 'perfil', 'tipos_fichas_catalograficas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFichaCatalograficaRequest $request)
    {
        $idUser = Auth::user()->id;
        $aluno = Aluno::where('user_id', $idUser)->first();
        $perfil = Perfil::where('aluno_id', $aluno->id)->first();
        $unidade = Unidade::where('id', $perfil->unidade_id)->first();

        $dados = $request->validated();

        DB::transaction(function () use ($request, $dados, $aluno, $perfil, $unidade) {
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

            $dadosFicha['tipo_ficha_catalografica_id'] = $dados['tipo_ficha_catalografica_id'];
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

            match ((int) $dados['tipo_ficha_catalografica_id']) {
                1 => $ficha->dissertacao()->create([
                    'campus' => $unidade->nome,
                    'programa' => $dados['programa'],
                ]),

                2 => $ficha->monografia()->create([
                    'curso' => $perfil->default,
                    'tipo_curso' => $dados['tipo_curso'],
                    'campus' => $unidade->nome,
                ]),

                3 => $ficha->programaEducacional()->create([
                    'programa' => $dados['programa'],
                    'campus' => $unidade->nome,
                    'produto' => $dados['produto'],
                ]),

                4 => $ficha->tese()->create([
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
                ->filter()
                ->map(fn ($palavra) => ['palavra' => $palavra])
                ->values()
                ->all();

            $ficha->palavrasChave()->createMany($palavras);

            return $ficha;
        });


        return redirect(Route('fichas.index'))->with('success', 'Ficha catalográfica criada com sucesso!');
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
     * @param  \App\Models\FichaCatalografica  $fichaCatalografica
     * @return \Illuminate\Http\Response
     */
    public function edit(FichaCatalografica $ficha)
    {
        $tipos_fichas_catalograficas = TipoFichaCatalografica::whereIn('slug', ['dissertacao', 'monografia'])->get();
        $ficha->load(['tipoFichaCatalografica', 'dissertacao', 'monografia', 'palavrasChave', 'aluno']);
        $aluno = $ficha->aluno;
        $perfil = $aluno->perfil()->where('valor', true)->first();
        $curso = $perfil->curso;
        return view('fichas_catalograficas.edit', compact('ficha', 'tipos_fichas_catalograficas', 'aluno', 'perfil', 'curso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FichaCatalografica  $fichaCatalografica
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFichaCatalograficaRequest $request, $ficha_catalografica_id)
    {
        $user = Auth::user();

        $ficha = FichaCatalografica::findOrFail($ficha_catalografica_id);

        if ($user->tipo === 'aluno' && $ficha->aluno->user_id !== $user->id) {
            return back()->with('fail', 'Você não tem permissão para editar esta ficha.');
        }

        $aluno = $ficha->aluno;
        $perfil = Perfil::where('aluno_id', $aluno->id)->first();
        $unidade = Unidade::findOrFail($perfil->unidade_id);

        $dados = $request->validated();

        DB::transaction(function () use ($request, $dados, $aluno, $perfil, $unidade, $ficha) {
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

            $dadosFicha['tipo_ficha_catalografica_id'] = $dados['tipo_ficha_catalografica_id'];
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

            $ficha->update($dadosFicha);

            $tipoDocumento = (int) $dados['tipo_ficha_catalografica_id'];

            if ($tipoDocumento !== 1) {
                $ficha->dissertacao()->delete();
            }

            if ($tipoDocumento !== 2) {
                $ficha->monografia()->delete();
            }

            // if ($tipoDocumento !== 3) {
            //     $ficha->programaEducacional()->delete();
            // }

            // if ($tipoDocumento !== 4) {
            //     $ficha->tese()->delete();
            // }

            match ($tipoDocumento) {
                1 => $ficha->dissertacao()->updateOrCreate(
                    [],
                    [
                        'campus' => $unidade->nome,
                        'programa' => $dados['programa'],
                    ]
                ),

                2 => $ficha->monografia()->updateOrCreate(
                    [],
                    [
                        'curso' => $perfil->default,
                        'tipo_curso' => $dados['tipo_curso'],
                        'campus' => $unidade->nome,
                    ]
                ),

                // 3 => $ficha->programaEducacional()->updateOrCreate(
                //     [],
                //     [
                //         'programa' => $dados['programa'],
                //         'campus' => $unidade->nome,
                //         'produto' => $dados['produto'],
                //     ]
                // ),

                // 4 => $ficha->tese()->updateOrCreate(
                //     [],
                //     [
                //         'programa' => $dados['programa'],
                //     ]
                // ),
            };

            $palavras = collect([
                $dados['primeira_chave'],
                $dados['segunda_chave'],
                $dados['terceira_chave'],
                $dados['quarta_chave'] ?? null,
                $dados['quinta_chave'] ?? null,
            ])
                ->filter()
                ->map(fn ($palavra) => ['palavra' => $palavra])
                ->values()
                ->all();

            $ficha->palavrasChave()->delete();
            $ficha->palavrasChave()->createMany($palavras);


        });

        if(Auth::user()->tipo == 'bibliotecario') {
            return redirect()->back()->with('success', 'Ficha atualizada com sucesso!');
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

        $ficha = FichaCatalografica::find($ficha_catalografica_id);
        $palavras = PalavraChave::where('ficha_catalografica_id', $ficha->id)->orderBy('id')->get();
        $tipo_ficha = TipoFichaCatalografica::find($ficha->tipo_ficha_catalografica_id)->slug;
        $unidade = Unidade::find($ficha->aluno->perfil->unidade_id);
        if ($tipo_ficha == 'monografia')
            $documento = Monografia::where('ficha_catalografica_id', $ficha->id)->first();
        elseif ($tipo_ficha == 'tese')
            $documento = Tese::where('ficha_catalografica_id', $ficha->id)->first();
        elseif ($tipo_ficha == 'TCC')
            $documento = Tcc::where('ficha_catalografica_id', $ficha->id)->first();
        elseif ($tipo_ficha == 'programa_educacional')
            $documento = ProgramaEducacional::where('ficha_catalografica_id', $ficha->id)->first();
        else
            $documento = Dissertacao::where('ficha_catalografica_id', $ficha->id)->first();

        $pdf = Pdf::loadView('telas_bibliotecario.gerar_ficha', compact('ficha', 'palavras', 'tipo_ficha', 'documento', 'unidade'));
        return $pdf->download($ficha->titulo . "_" . $ficha->autor . strtotime('now') . ".pdf");
    }
}
