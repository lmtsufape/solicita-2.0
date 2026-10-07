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
use App\Models\TipoDocumento;
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
        $tipos_documentos = TipoDocumento::whereIn('tipo', ['Dissertacao', 'Monografia'])->get();

        $aluno = $usuario->aluno;
        $perfil = $usuario->aluno->perfil()->where('valor', true)->first();
        $curso = $perfil->curso;

        return view('fichas_catalograficas.create', compact('usuario', 'aluno', 'curso', 'perfil', 'tipos_documentos'));
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

            $dadosFicha['tipo_documento_id'] = $dados['tipo_documento'];
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

            match ((int) $dados['tipo_documento']) {
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


        return redirect(Route('home-aluno'))->with('success', 'Solicitação realizada com sucesso!');
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
        $usuario = Auth::user();
        $tipos_documentos = TipoDocumento::whereIn('tipo', ['Dissertacao', 'Monografia'])->get();
        $ficha->load(['tipo', 'dissertacao', 'monografia', 'palavrasChave']);
        $aluno = $usuario->aluno;
        $perfil = $usuario->aluno->perfil()->where('valor', true)->first();
        $curso = $perfil->curso;
        return view('fichas_catalograficas.edit', compact('ficha', 'tipos_documentos', 'aluno', 'perfil', 'curso'));
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
        $idUser = Auth::user()->id;
        $aluno = Aluno::where('user_id', $idUser)->first();
        $perfil = Perfil::where('aluno_id', $aluno->id)->first();
        $unidade = Unidade::where('id', $perfil->unidade_id)->first();
        $ficha = $aluno->fichasCatalograficas()->findOrFail($ficha_catalografica_id);
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

            $dadosFicha['tipo_documento_id'] = $dados['tipo_documento'];
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

            $tipoDocumento = (int) $dados['tipo_documento'];

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

        return redirect(Route('listar-fichas'))->with('success', 'Ficha Atualizada com Sucesso!');
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
        $tipo_documento = TipoDocumento::find($ficha->tipo_documento_id)->tipo;
        $unidade = Unidade::find($ficha->alunos->perfil->unidade_id);
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

        $pdf = Pdf::loadView('telas_bibliotecario.gerar_ficha', compact('ficha', 'palavras', 'tipo_documento', 'documento', 'unidade'));
        return $pdf->download($ficha->titulo . "_" . $ficha->autor . strtotime('now') . ".pdf");
    }
}
