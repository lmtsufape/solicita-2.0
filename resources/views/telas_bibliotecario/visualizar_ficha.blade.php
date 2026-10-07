@extends('layouts.app')

@section('conteudo')
    <!-- @section('navbar2.blade.php')
    Home
@endsection -->

<style>
    select[readonly] {
        background: #eee; /*Simular campo inativo - Sugestão @GabrielRodrigues*/
        pointer-events: none;
        touch-action: none;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="row mt-5 tituloFicha">
                <div class="col-md-12">
                    Ficha Catalográfica -
                    @if($tipo_documento == 2)Monografia
                    @elseif($tipo_documento == 4)Tese
                    @elseif($tipo_documento == 3)Produto Educacional
                    @elseif($tipo_documento == 1)Dissertação
                    @endif
                </div>
            </div>
            <form method="POST" enctype="multipart/form-data" id="formRequisicao"
                  action="{{route('preview', ['requisicao_id' => $requisicao] )}}">
                @csrf
                <input type="hidden" readonly name="tipo_documento" value="{{$tipo_documento}}">

                <! –– Dados Pessoais ––>

                <div class="col-md-12 corpoFicha shadow my-4">
                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Dados Pessoais</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group py-2">
                                <label class="textoFicha" for="exampleFormControlInput1">Nome<span
                                        style="color: red">*</span>:</label>
                                <input type="text" class="form-control" id="nome" readonly name="nome"
                                       placeholder="Nome"
                                       value="{{\App\Models\User::where('id', $aluno->user_id)->first()->name}}"
                                       disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <! -- Dados para o bibliotecário -- !>
                <div class="col-md-12 corpoFicha shadow my-4">
                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Dados para o Bibliotecário(a)</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 py-2 textoFicha">
                            <div class="form-group">
                                <label for="cutter">Cutter: <span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="cutter" readonly name="cutter"
                                       placeholder="Digite o código de Cutter" value="{{ $fichaCatalografica->cutter }}"
                                        readonly>
                            </div>

                            <div class="form-group">
                                <label for="area_conhecimento">Área do Conhecimento: <span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="area_conhecimento" readonly name="area_conhecimento"
                                       placeholder="Digite a área do conhecimento" value="{{ $fichaCatalografica->area_conhecimento  }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <! –– Dados do Trabalho ––>

                <div class="col-md-12 corpoFicha shadow my-4">

                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Dados do Trabalho</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 py-2 textoFicha">
                            <div class="form-group">
                                <label for="autor_nome">Nome: <span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="autor_nome" readonly name="autor_nome"
                                       placeholder="Digite o nome do Autor" value="{{$fichaCatalografica->autor_nome}}"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="autor_sobrenome">Sobrenome: <span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="autor_sobrenome" readonly name="autor_sobrenome"
                                       placeholder="Digite o sobrenome do Autor"
                                       value="{{$fichaCatalografica->autor_sobrenome}}" required>
                            </div>
                            <div class="form-group">
                                <label for="titulo">Título: <span style="color: red">*</span></label>
                                <div class="disabled">{!! $fichaCatalografica->titulo !!}</div>
                                <input type="hidden" name="titulo" value="{{ $fichaCatalografica->titulo }}">
                            </div>
                            <div class="form-group">
                                <label for="subtitulo">Subtítulo: <span style="color: red">@if($tipo_documento != 1 && $tipo_documento != 5)
                                            * @endif</span> </label>
                                <div class="disabled">{!! $fichaCatalografica->subtitulo !!}</div>
                                <input type="hidden" name="subtitulo" value="{{ $fichaCatalografica->subtitulo }}">
                            </div>
                            <div class="form-group">
                                <label for="local">Local: <span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="local" readonly name="local"
                                       placeholder="Digite o Local" value="{{$fichaCatalografica->local}}" required>
                            </div>
                            <div class="row justify-content-between">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ano">Ano: <span style="color: red">*</span></label>
                                        <input class="form-control" type="number" min="1900" max="2099" step="1"
                                               readonly name="ano"
                                               value="{{$fichaCatalografica->ano}}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="folhas">Folhas: <span style="color: red">*</span></label>
                                        <input type="number" class="form-control" id="folhas" readonly name="folhas"
                                               placeholder="Quantidade de Folhas"
                                               value="{{$fichaCatalografica->folhas}}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ilustracao">Ilustração: <span style="color: red">*</span></label>
                                        <select class="form-control" id="ilustracao" readonly name="ilustracao">
                                            <option value="colorido"
                                                    @if($fichaCatalografica->ilustracao == 'colorido') selected @endif>
                                                Colorido
                                            </option>
                                            <option value="preto_branco"
                                                    @if($fichaCatalografica->ilustracao == 'preto_branco') selected @endif>
                                                Preto e Branco
                                            </option>
                                            <option value="nao_possui"
                                                    @if($fichaCatalografica->ilustracao == 'nao_possui') selected @endif>
                                                Não Possui
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-between">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inclui_anexo">Inclui Anexo? <span style="color: red">*</span></label>
                                        <select class="form-control" id="inclui_anexo" name="inclui_anexo" readonly="readonly">
                                            <option @if($fichaCatalografica->inclui_anexo == 0) selected @endif value="0">Não</option>
                                            <option @if($fichaCatalografica->inclui_anexo == 1) selected @endif value="1">Sim</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inclui_apendice">Inclui Apêndice? <span style="color: red">*</span></label>
                                        <select class="form-control" id="inclui_apendice" name="inclui_apendice" readonly="readonly">
                                            <option @if($fichaCatalografica->inclui_apendice == 0) selected @endif value="0">Não</option>
                                            <option @if($fichaCatalografica->inclui_apendice == 1) selected @endif value="1">Sim</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="forma-group">
                                    <label for="anexoArquivo">Visualizar Anexo: <span style="color: red">*</span>
                                    </label><br>
                                    <a class="btn btn-primary" href="{{ route('baixar-anexo', $requisicao->id) }}"
                                       style="margin-bottom: 10px">Baixar anexo</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <! –– Dados Especificos ––>

                @if($tipo_documento == 2) <! -- MONOGRAFIA --!>
                <div class="col-md-12 corpoFicha shadow my-4">
                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Monografia</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 py-2 textoFicha">
                            <div class="form-group">
                                <label for="nome_orientador">Nome do Orientador: <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control" id="nome_orientador" readonly name="nome_orientador"
                                       placeholder="Digite o nome do orientador" value="{{$documento->nome_orientador}}"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="sobrenome_orientador">Sobrenome do Orientador: <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control" id="sobrenome_orientador"
                                       readonly name="sobrenome_orientador"
                                       placeholder="Digite o Sobrenome do orientador"
                                       value="{{$documento->sobrenome_orientador}}" required>
                            </div>

                            <div class="form-group">
                                <label for="nome_coorientador">Nome do Coorientador: </label>
                                <input type="text" class="form-control" id="nome_coorientador"
                                       placeholder="Digite o Nome do Coorientador"
                                       value="{{$documento->nome_coorientador}}" readonly name="nome_coorientador">
                            </div>

                            <div class="form-group">
                                <label for="sobrenome_coorientador">Sobrenome do Coorientador: </label>
                                <input type="text" class="form-control" id="sobrenome_coorientador"
                                       placeholder="Digite o Sobrenome do Coorientador"
                                       value="{{$documento->sobrenome_coorientador}}"
                                       readonly name="sobrenome_coorientador">
                            </div>

                            <div class="form-group">
                                <label for="nome_segundo_coorientador">Nome do Segundo Coorientador: </label>
                                <input type="text" class="form-control" id="nome_segundo_coorientador"
                                       placeholder="Digite o Nome do Segundo Coorientador"
                                       value="{{$documento->nome_segundo_coorientador}}" readonly name="nome_segundo_coorientador">
                            </div>

                            <div class="form-group">
                                <label for="sobrenome_segundo_coorientador">Sobrenome do Segundo Coorientador: </label>
                                <input type="text" class="form-control" id="sobrenome_segundo_coorientador" readonly
                                       placeholder="Digite o Sobrenome do Segundo Coorientador"
                                       value="{{$documento->sobrenome_segundo_coorientador}}"
                                       name="sobrenome_segundo_coorientador">
                            </div>

                            <div class="row justify-content-between">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tipo_curso">Selecione o Tipo do seu Curso: <span
                                                style="color: red">*</span></label>
                                        <select class="form-control" aria-label="Selecione o tipo do curso"
                                                readonly name="tipo_curso" id="tipo_curso">
                                            <option value="especializacao"
                                                    @if($documento->tipo_curso == 'especializacao') selected @endif>
                                                Especialização
                                            </option>
                                            <option value="graduacao"
                                                    @if($documento->tipo_curso == 'graduacao') selected @endif>Graduação
                                            </option>
                                            <option value="mestrado" @if($documento->tipo_curso == 'mestrado') selected @endif>
                                                Mestrado
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="curso">Curso: <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" id="curso" readonly name="curso"
                                               placeholder="Digite o Nome do Curso" value="{{$documento->curso}}"
                                               required
                                               disabled>
                                        <input type="hidden" class="form-control" id="curso" readonly name="curso"
                                               placeholder="Digite o Nome do Curso" value="{{$documento->curso}}"
                                               required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="campus">Campus: <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" id="campus" readonly name="campus"
                                               placeholder="Digite o Nome do Campus" value="{{$documento->campus}}"
                                               required
                                               disabled>
                                        <input type="hidden" class="form-control" id="campus" readonly name="campus"
                                               placeholder="Digite o Nome do Campus" value="{{$documento->campus}}"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($tipo_documento == 4) <! -- TESE --!>
                <div class="col-md-12 corpoFicha shadow my-4">
                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Tese</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 py-2 textoFicha">
                            <div class="form-group">
                                <label for="nome_orientador">Nome do Orientador: <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control" id="nome_orientador" readonly name="nome_orientador"
                                       placeholder="Digite o Nome do Orientador"
                                       value="{{$documento->nome_orientador}}">
                            </div>

                            <div class="form-group">
                                <label for="sobrenome_orientador">Sobrenome do Orientador: <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control" id="sobrenome_orientador"
                                       readonly name="sobrenome_orientador"
                                       placeholder="Digite o Sobrenome do orientador"
                                       value="{{$documento->sobrenome_orientador}}" required>
                            </div>

                            <div class="form-group">
                                <label for="nome_coorientador">Nome do coorientador:</label>
                                <input type="text" class="form-control" id="nome_coorientador" readonly name="nome_coorientador"
                                       placeholder="Digite o Nome do Coorientador"
                                       value="{{$documento->nome_coorientador}}">
                            </div>

                            <div class="form-group">
                                <label for="sobrenome_coorientador">Sobrenome do Coorientador: </label>
                                <input type="text" class="form-control" id="sobrenome_coorientador"
                                       placeholder="Digite o Sobrenome do Coorientador"
                                       value="{{$documento->sobrenome_coorientador}}"
                                       readonly name="sobrenome_coorientador">
                            </div>

                            <div class="form-group">
                                <label for="programa">Nome do Programa de Pós-Graduação <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control" id="programa" readonly name="programa"
                                       placeholder="Digite o Nome do Programa" value="{{$documento->programa}}">
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($tipo_documento == 3)
                    <div class="col-md-12 corpoFicha shadow my-4">
                        <div class="row">
                            <div class="col-md-12 cabecalho py-2">
                                <span class="tituloCabecalho">Produto Educacional</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 textoFicha py-2">

                                <div class="form-group">
                                    <label for="programa">Nome do Programa de Pós-Graduação <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="programa" readonly name="programa"
                                           placeholder="Digite o Nome do Programa" value="{{$documento->programa}}">
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="curso">Curso: <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="curso" readonly name="curso"
                                           placeholder="Digite o Nome do Curso" value="{{$documento->curso}}"
                                           required
                                           disabled>
                                    <input type="hidden" class="form-control" id="curso" readonly name="curso"
                                           placeholder="Digite o Nome do Curso" value="{{$documento->curso}}"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="campus">Campus: <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="campus" readonly name="campus"
                                           placeholder="Digite o Nome do Campus" value="{{$documento->campus}}"
                                           required
                                           disabled>
                                    <input type="hidden" class="form-control" id="campus" readonly name="campus"
                                           placeholder="Digite o Nome do Campus" value="{{$documento->campus}}"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($tipo_documento == 1)
                    <div class="col-md-12 corpoFicha shadow my-4">
                        <div class="row">
                            <div class="col-md-12 cabecalho py-2">
                                <span class="tituloCabecalho">Dissertação</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 py-2 textoFicha">
                                <div class="form-group">
                                    <label for="nome_orientador">Nome do Orientador: <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="nome_orientador" readonly name="nome_orientador"
                                           placeholder="Digite o Nome do Orientador"
                                           value="{{$documento->nome_orientador}}" required>
                                </div>

                                <div class="form-group">
                                    <label for="sobrenome_orientador">Sobrenome do Orientador: <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="sobrenome_orientador"
                                           readonly name="sobrenome_orientador"
                                           placeholder="Digite o Sobrenome do Orientador"
                                           value="{{$documento->sobrenome_orientador}}" required>
                                </div>

                                <div class="form-group">
                                    <label for="nome_coorientador">Nome do Coorientador: </label>
                                    <input type="text" class="form-control" id="nome_coorientador"
                                           placeholder="Digite o Nome do Coorientador"
                                           value="{{$documento->nome_coorientador}}"
                                           readonly name="nome_coorientador">
                                </div>

                                <div class="form-group">
                                    <label for="sobrenome_coorientador">Sobrenome do Coorientador: </label>
                                    <input type="text" class="form-control" id="sobrenome_coorientador"
                                           placeholder="Digite o Sobrenome do Coorientador"
                                           value="{{$documento->sobrenome_coorientador}}"
                                           readonly name="sobrenome_coorientador">
                                </div>

                                <div class="form-group">
                                    <label for="nome_segundo_coorientador">Nome do Segundo Coorientador: </label>
                                    <input type="text" class="form-control" id="nome_segundo_coorientador"
                                           placeholder="Digite o Nome do Segundo Coorientador"
                                           value="{{$documento->nome_segundo_coorientador}}" readonly name="nome_segundo_coorientador">
                                </div>

                                <div class="form-group">
                                    <label for="sobrenome_segundo_coorientador">Sobrenome do Segundo Coorientador: </label>
                                    <input type="text" class="form-control" id="sobrenome_segundo_coorientador" readonly
                                           placeholder="Digite o Sobrenome do Segundo Coorientador"
                                           value="{{$documento->sobrenome_segundo_coorientador}}"
                                           name="sobrenome_segundo_coorientador">
                                </div>

                                <div class="form-group">
                                    <label for="programa">Nome do Programa de Pós-Graduação <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="programa" readonly name="programa"
                                           placeholder="Digite o Nome do Programa" value="{{$documento->programa}}">
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="curso">Curso: <span style="color: red">*</span></label>
                                            <input type="text" class="form-control" id="curso" readonly name="curso"
                                                   placeholder="Digite o Nome do Curso" value="{{$documento->curso}}"
                                                   required
                                                   disabled>
                                            <input type="hidden" class="form-control" id="curso" readonly name="curso"
                                                   placeholder="Digite o Nome do Curso" value="{{$documento->curso}}"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="campus">Campus: <span style="color: red">*</span></label>
                                            <input type="text" class="form-control" id="campus" readonly name="campus"
                                                   placeholder="Digite o Nome do Campus" value="{{$documento->campus}}"
                                                   required
                                                   disabled>
                                            <input type="hidden" class="form-control" id="campus" readonly name="campus"
                                                   placeholder="Digite o Nome do Campus" value="{{$documento->campus}}"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif

                <! -- PALAVRAS CHAVE -- !>
                <div class="col-md-12 corpoFicha shadow my-4">
                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Palavras-chave</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 py-2 textoFicha">
                            <div class="form-group">
                                <label for="primeira">Primeira Palavra:<span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="primeira" readonly name="primeira_chave"
                                       placeholder="1. Palavras-chave" value="{{$palavrasChave[0]->palavra}}">
                            </div>

                            <div class="form-group">
                                <label for="segunda">Segunda Palavra:<span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="segunda" readonly name="segunda_chave"
                                       placeholder="2. Palavras-chave" value="{{$palavrasChave[1]->palavra}}">
                            </div>

                            <div class="form-group">
                                <label for="terceira">Terceira Palavra:<span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="terceira" readonly name="terceira_chave"
                                       placeholder="3. Palavras-chave" value="{{$palavrasChave[2]->palavra}}">
                            </div>

                            @if(sizeof($palavrasChave) >= 4 )
                                <input type="hidden" readonly name="palavra_chave4_id" value="{{$palavrasChave[3]->id}}">
                                <div class="form-group">
                                    <label for="quarta">Quarta Palavra:</label>
                                    <input type="text" class="form-control" id="quarta" readonly name="quarta_chave"
                                           placeholder="4. Palavras-chave" value="{{$palavrasChave[3]->palavra}} ">
                                </div>
                                @if(sizeof($palavrasChave) == 5)
                                    <input type="hidden" readonly name="palavra_chave5_id" value="{{$palavrasChave[4]->id}}">
                                    <div class="form-group">
                                        <label for="quinta">Quinta Palavra:</label>
                                        <input type="text" class="form-control" id="quinta" readonly name="quinta_chave"
                                               placeholder="5. Palavras-chave" value="{{$palavrasChave[4]->palavra}} ">
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row justify-content-between mt-5">
                    <div class="col-md-4">
                        <a type="button" class="btn btn-block"
                           style="background-color: var(--padrao); border-radius: 0.5rem; color: white;"
                           href="{{ route('listar-fichas') }}">Voltar</a>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-block" id="btn_gerar_ficha"
                           style="background-color: #1A2876; border-radius: 0.5rem; color: white;">Visualizar Ficha</button>
                    </div>
                    <input type="hidden" name="tipo_documento" value="{{$tipo_documento}}">
                    <input type="hidden" name="aluno_id" value="{{$aluno->id}}">
                    <input type="hidden" name="ficha_catalografica_id" value="{{$fichaCatalografica->id}}">
                    <input type="hidden" name="palavra_chave1_id" value="{{$palavrasChave[0]->id}}">
                    <input type="hidden" name="palavra_chave2_id" value="{{$palavrasChave[1]->id}}">
                    <input type="hidden" name="palavra_chave3_id" value="{{$palavrasChave[2]->id}}">
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@endsection
