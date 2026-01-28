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
                        Comprovante deposito
                    </div>
                </div>
                <form method="POST" enctype="multipart/form-data" id="formRequisicao"
                      action="{{route('preview', ['requisicao_id' => $requisicao])}}">
                    @csrf
                    <input type="hidden" readonly name="tipo_documento" value="{{$deposito}}">

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
                                    <label class="pt-2 textoFicha" for="exampleFormControlInput1">Nome<span
                                            style="color: red">*</span>:</label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           placeholder="Nome"
                                           value="{{$deposito->autor_nome ?? $aluno->user->name}}"
                                           disabled>

                                    <label class="pt-2 textoFicha" for="exampleFormControlInput1">CPF<span
                                            style="color: red">*</span>:</label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           placeholder="Nome"
                                           value="{{$aluno->cpf}}"
                                           disabled>

                                    <label class="pt-2 textoFicha" for="exampleFormControlInput1">Curso<span
                                            style="color: red">*</span>:</label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           placeholder="Nome"
                                           value="{{$requisicao->perfil->curso->nome}}"
                                           disabled>
                                    <label class="pt-2 textoFicha" for="exampleFormControlInput1">Título do trabalho<span
                                            style="color: red">*</span>:</label>
                                    <div class="disabled">{!! $requisicao_documento->deposito->titulo_tcc !!}</div>
                                    <div class="form-group pt-2">
                                        <label for="registro_patente" class="textoFicha">Registro de patente: <span style="color: red">*</span></label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="registro_patente" id="registro_patente_sim" value="true" required disabled {{ old('registro_patente', ($deposito->registro_patente ? 'true' : 'false')) == 'true' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="registro_patente_sim">Sim</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="registro_patente" id="registro_patente_nao" value="false" required disabled {{ old('registro_patente', ($deposito->registro_patente ? 'true' : 'false')) == 'false' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="registro_patente_nao">Não</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="forma-group">
                                            <label class="pt-2 textoFicha" for="anexoArquivo">Trabalho de Conclusão de Curso (TCC):<span
                                                    style="color: red">*</span>:</label><br>
                                            <a class="btn btn-primary" href="{{ route('baixar-anexo-tcc-deposito', $requisicao_documento->id) }}">
                                                Visualizar
                                            </a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="forma-group">
                                            <label class="pt-2 textoFicha" for="anexoArquivo">Termo de autorização:<span
                                                    style="color: red">*</span>:</label><br>
                                            <a class="btn btn-primary" href="{{ route('baixar-anexo-autorizacao-deposito', $requisicao_documento->id) }}">
                                                Visualizar
                                            </a>
                                        </div>
                                    </div>
                                    @if(isset($deposito->anexo_comprovante_deposito))
                                        <div class="form-group">
                                            <div class="forma-group">
                                                <label class="pt-2 textoFicha" for="anexoArquivo">Comprovante de deposito:</label><br>
                                                <a class="btn btn-primary" href="{{ route('baixar-anexo-comprovante-deposito', $requisicao_documento->id) }}"
                                                   style="margin-bottom: 10px">Visualizar</a>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-between mt-5">
                        <div class="col-md-4">
                            <a type="button" class="btn btn-block"
                               style="background-color: var(--padrao); border-radius: 0.5rem; color: white;"
                               href="{{ route('listar-fichas') }}">Voltar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

@endsection
